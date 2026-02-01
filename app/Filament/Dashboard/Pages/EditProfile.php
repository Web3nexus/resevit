<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\ReservationSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.dashboard.pages.edit-profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public ?array $businessData = [];

    public function mount(): void
    {
        $user = auth()->user();
        $this->profileData = [
            'name' => $user->name,
            'email' => $user->email,
            'currency' => $user->currency,
            'timezone' => $user->timezone,
            'locale' => $user->locale ?? 'en',
        ];

        $settings = ReservationSetting::getInstance();
        $this->businessData = [
            'business_name' => $settings->business_name,
            'business_address' => $settings->business_address,
            'business_phone' => $settings->business_phone,
            'business_hours' => $settings->business_hours,
            'business_logo' => $settings->business_logo,
            'social_links' => $settings->social_links ?? [],
            'currency' => $settings->currency,
            'timezone' => $settings->timezone,
        ];
    }

    protected function getSchemas(): array
    {
        return [
            'profileForm',
            'passwordForm',
            'businessForm',
        ];
    }

    public function profileForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Profile Information')
                    ->description('Update your account\'s profile information and email address.')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('currency')
                            ->options(app(\App\Services\CurrencyService::class)->getSupportedCurrencies())
                            ->required(),
                        Select::make('timezone')
                            ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                            ->searchable()
                            ->required(),
                        Select::make('locale')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'es' => 'Spanish',
                                'fr' => 'French',
                                'de' => 'German',
                                'ar' => 'Arabic',
                            ])
                            ->required(),
                        FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->avatar()
                            ->imageEditor()
                            ->directory('avatars')
                            ->getUploadedFileUsing(fn ($record) => \App\Helpers\StorageHelper::getUrl($record->avatar_url ?? $record->profileData['avatar_url'] ?? null)),
                    ])
                    ->columns(2),
            ])
            ->statePath('profileData')
            ->model(auth()->user());
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Update Password')
                    ->description('Ensure your account is using a long, random password to stay secure.')
                    ->schema([
                        TextInput::make('current_password')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->same('password_confirmation'),
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->autocomplete('new-password'),
                    ]),
            ])
            ->statePath('passwordData');
    }

    public function businessForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Business Details')
                    ->description('Manage your business information and operating hours.')
                    ->schema([
                        TextInput::make('business_name')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('business_logo')
                            ->label('Business Logo')
                            ->image()
                            ->directory('business-logos')
                            ->columnSpanFull(),
                        Section::make('Social Media')
                            ->schema([
                                Repeater::make('social_links')
                                    ->schema([
                                        Select::make('platform')
                                            ->options([
                                                'facebook' => 'Facebook',
                                                'instagram' => 'Instagram',
                                                'twitter' => 'Twitter/X',
                                                'linkedin' => 'LinkedIn',
                                                'tiktok' => 'TikTok',
                                                'youtube' => 'YouTube',
                                            ])
                                            ->required(),
                                        TextInput::make('url')
                                            ->label('URL')
                                            ->url()
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Social Link'),
                            ])->collapsed(),
                        TextInput::make('business_phone')
                            ->tel()
                            ->maxLength(20),
                        Textarea::make('business_address')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('currency')
                            ->options(app(\App\Services\CurrencyService::class)->getSupportedCurrencies())
                            ->required(),
                        Select::make('timezone')
                            ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                            ->searchable()
                            ->required(),

                        KeyValue::make('business_hours')
                            ->label('Weekly Schedule')
                            ->keyLabel('Day')
                            ->valueLabel('Hours')
                            ->helperText('Format: {"open": "09:00", "close": "22:00", "closed": false}')
                            ->columnSpanFull(),
                    ])->columns(2),
            ])
            ->statePath('businessData');
    }

    public function updateProfile(): void
    {
        $data = $this->profileForm->getState();

        $user = auth()->user();
        $user->update($data);

        Notification::make()
            ->success()
            ->title('Profile updated')
            ->send();
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();

        auth()->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->passwordData = [];
        $this->passwordForm->fill();

        Notification::make()
            ->success()
            ->title('Password updated')
            ->send();
    }

    public function updateBusiness(): void
    {
        $data = $this->businessForm->getState();

        $settings = ReservationSetting::getInstance();
        $settings->update($data);

        Notification::make()
            ->success()
            ->title('Business details updated')
            ->send();
    }
}
