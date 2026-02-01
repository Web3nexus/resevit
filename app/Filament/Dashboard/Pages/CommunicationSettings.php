<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class CommunicationSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected string $view = 'filament.dashboard.pages.communication-settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Load existing settings or defaults
        // For simplicity, using a JSON structure or separate tables
        $emailSettings = \Illuminate\Support\Facades\DB::table('email_settings')->first();
        $smsSettings = \Illuminate\Support\Facades\DB::table('sms_settings')->first();

        $this->form->fill([
            'email_provider' => $emailSettings->provider ?? 'smtp',
            'smtp_host' => $emailSettings->smtp_host ?? '',
            'smtp_port' => $emailSettings->smtp_port ?? 587,
            'smtp_username' => $emailSettings->smtp_username ?? '',
            'smtp_password' => $emailSettings->smtp_password ?? '', // don't show real password
            'from_email' => $emailSettings->from_email ?? '',
            'from_name' => $emailSettings->from_name ?? '',

            'sms_provider' => $smsSettings->provider ?? 'twilio',
            'twilio_sid' => $smsSettings->api_key ?? '', // Assuming api_key used for SID
            'twilio_token' => $smsSettings->api_secret ?? '',
            'twilio_from' => $smsSettings->from_number ?? '',
        ]);
    }

    protected function getSchemas(): array
    {
        return [
            'form',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Email Configuration')
                    ->schema([
                        Forms\Components\Select::make('email_provider')
                            ->options([
                                'smtp' => 'SMTP',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                            ])
                            ->required(),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('smtp_host')
                                    ->label('SMTP Host')
                                    ->visible(fn ($get) => $get('email_provider') === 'smtp'),
                                Forms\Components\TextInput::make('smtp_port')
                                    ->label('SMTP Port')
                                    ->numeric()
                                    ->visible(fn ($get) => $get('email_provider') === 'smtp'),
                                Forms\Components\TextInput::make('smtp_username')
                                    ->label('Username')
                                    ->visible(fn ($get) => $get('email_provider') === 'smtp'),
                                Forms\Components\TextInput::make('smtp_password')
                                    ->label('Password')
                                    ->password()
                                    ->visible(fn ($get) => $get('email_provider') === 'smtp'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('from_email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('from_name')
                                    ->required(),
                            ]),
                    ]),

                Section::make('SMS Configuration')
                    ->schema([
                        Forms\Components\Select::make('sms_provider')
                            ->options([
                                'twilio' => 'Twilio',
                                'vonage' => 'Vonage (Nexmo)',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('twilio_sid')
                            ->label('Account SID')
                            ->visible(fn ($get) => $get('sms_provider') === 'twilio'),
                        Forms\Components\TextInput::make('twilio_token')
                            ->label('Auth Token')
                            ->password()
                            ->visible(fn ($get) => $get('sms_provider') === 'twilio'),
                        Forms\Components\TextInput::make('twilio_from')
                            ->label('From Number')
                            ->visible(fn ($get) => $get('sms_provider') === 'twilio'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save Email Settings
        \Illuminate\Support\Facades\DB::table('email_settings')->updateOrInsert(
            ['id' => 1], // Assuming single row per tenant
            [
                'provider' => $data['email_provider'],
                'smtp_host' => $data['smtp_host'],
                'smtp_port' => $data['smtp_port'],
                'smtp_username' => $data['smtp_username'],
                'smtp_password' => $data['smtp_password'], // Should encrypt
                'from_email' => $data['from_email'],
                'from_name' => $data['from_name'],
                'updated_at' => now(),
            ]
        );

        // Save SMS Settings
        \Illuminate\Support\Facades\DB::table('sms_settings')->updateOrInsert(
            ['id' => 1],
            [
                'provider' => $data['sms_provider'],
                'api_key' => $data['twilio_sid'],
                'api_secret' => $data['twilio_token'], // Should encrypt
                'from_number' => $data['twilio_from'],
                'updated_at' => now(),
            ]
        );

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
