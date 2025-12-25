<?php

namespace App\Filament\Securegate\Pages;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use App\Models\DefaultSmsSetting;
use App\Models\DefaultEmailSetting;
use Filament\Schemas\Components\Form;
use Filament\Notifications\Notification;

class CommunicationSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected string $view = 'filament.securegate.pages.communication-settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Settings';

    protected static ?string $navigationLabel = 'Email & SMS Settings';

    protected static ?string $title = 'Email & SMS Provider Settings';

    public ?array $emailData = [];
    public ?array $smsData = [];

    public function mount(): void
    {
        $emailSettings = DefaultEmailSetting::first();
        $smsSettings = DefaultSmsSetting::first();

        $this->emailData = $emailSettings ? $emailSettings->toArray() : [];
        $this->smsData = $smsSettings ? $smsSettings->toArray() : [];
    }

    public function emailForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Default Email Provider')
                    ->description(
                        'Configure the platform-wide default email provider. Tenants without custom settings will use these defaults.'
                    )
                    ->schema([
                        Grid::make()
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->gap(6)
                            ->schema([
                                Forms\Components\Select::make('provider')
                                    ->label('Email Provider')
                                    ->options([
                                        'smtp' => 'SMTP',
                                        'sendgrid' => 'SendGrid',
                                        'mailgun' => 'Mailgun',
                                        'ses' => 'AWS SES',
                                    ])
                                    ->required()
                                    ->live()
                                    ->default('smtp'),

                                Forms\Components\TextInput::make('smtp_host')
                                    ->label('SMTP Host')
                                    ->visible(fn($get) => $get('provider') === 'smtp'),

                                Forms\Components\TextInput::make('smtp_port')
                                    ->label('SMTP Port')
                                    ->numeric()
                                    ->default(587)
                                    ->visible(fn($get) => $get('provider') === 'smtp'),

                                Forms\Components\TextInput::make('smtp_username')
                                    ->label('SMTP Username')
                                    ->visible(fn($get) => $get('provider') === 'smtp'),

                                Forms\Components\TextInput::make('smtp_password')
                                    ->label('SMTP Password')
                                    ->password()
                                    ->revealable()
                                    ->visible(fn($get) => $get('provider') === 'smtp'),

                                Forms\Components\Select::make('smtp_encryption')
                                    ->label('SMTP Encryption')
                                    ->options([
                                        'tls' => 'TLS',
                                        'ssl' => 'SSL',
                                    ])
                                    ->default('tls')
                                    ->visible(fn($get) => $get('provider') === 'smtp'),

                                Forms\Components\TextInput::make('api_key')
                                    ->label('API Key')
                                    ->password()
                                    ->revealable()
                                    ->visible(fn($get) => in_array($get('provider'), ['sendgrid', 'mailgun', 'ses'])),

                                Forms\Components\TextInput::make('api_region')
                                    ->label('AWS Region')
                                    ->default('us-east-1')
                                    ->visible(fn($get) => $get('provider') === 'ses'),

                                Forms\Components\TextInput::make('from_email')
                                    ->label('From Email')
                                    ->email()
                                    ->required(),

                                Forms\Components\TextInput::make('from_name')
                                    ->label('From Name')
                                    ->required(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Enable Email Sending')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])
            ->statePath('emailData');
    }


    public function smsForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Default SMS Provider')
                    ->description(
                        'Configure the platform-wide default SMS provider. Supported providers: Twilio, Vonage, MessageBird, and Plivo.'
                    )
                    ->schema([
                        Grid::make()
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->gap(6)
                            ->schema([
                                Forms\Components\Select::make('provider')
                                    ->label('SMS Provider')
                                    ->options([
                                        'twilio' => 'Twilio',
                                        'vonage' => 'Vonage (Nexmo)',
                                        'messagebird' => 'MessageBird',
                                        'plivo' => 'Plivo',
                                    ])
                                    ->required()
                                    ->live()
                                    ->default('twilio'),

                                Forms\Components\TextInput::make('api_key')
                                    ->label(fn($get) => match ($get('provider')) {
                                        'twilio' => 'Account SID',
                                        'vonage' => 'API Key',
                                        'messagebird' => 'Access Key',
                                        'plivo' => 'Auth ID',
                                        default => 'API Key',
                                    })
                                    ->password()
                                    ->revealable()
                                    ->required(),

                                Forms\Components\TextInput::make('api_secret')
                                    ->label(fn($get) => match ($get('provider')) {
                                        'twilio' => 'Auth Token',
                                        'vonage' => 'API Secret',
                                        'plivo' => 'Auth Token',
                                        default => 'API Secret',
                                    })
                                    ->password()
                                    ->revealable()
                                    ->required()
                                    ->visible(fn($get) => in_array($get('provider'), ['twilio', 'vonage', 'plivo'])),

                                Forms\Components\TextInput::make('from_number')
                                    ->label('From Number')
                                    ->tel()
                                    ->required()
                                    ->helperText('Include country code (e.g., +1234567890)'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Enable SMS Sending')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])
            ->statePath('smsData');
    }


    public function saveEmail(): void
    {
        $data = $this->emailForm->getState();

        DefaultEmailSetting::updateOrCreate(
            ['id' => $this->emailData['id'] ?? null],
            $data
        );

        Notification::make()
            ->title('Email settings saved successfully')
            ->success()
            ->send();
    }

    public function saveSms(): void
    {
        $data = $this->smsForm->getState();

        DefaultSmsSetting::updateOrCreate(
            ['id' => $this->smsData['id'] ?? null],
            $data
        );

        Notification::make()
            ->title('SMS settings saved successfully')
            ->success()
            ->send();
    }
}
