<?php

namespace App\Filament\Securegate\Pages;

use App\Services\CurrencyService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.securegate.pages.edit-profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public ?array $twoFactorData = [];

    public function mount(): void
    {
        $user = auth()->user();
        $this->profileData = [
            'name' => $user->name,
            'email' => $user->email,
            'currency' => $user->currency,
            'timezone' => $user->timezone,
        ];

        $this->twoFactorData = [
            'two_factor_enabled' => (bool) $user->two_factor_confirmed_at,
        ];
    }

    protected function getSchemas(): array
    {
        return [
            'profileForm',
            'passwordForm',
            'twoFactorForm',
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
                            ->options(app(CurrencyService::class)->getSupportedCurrencies())
                            ->required(),
                        Select::make('timezone')
                            ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                            ->searchable()
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('profileData');
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

    public function twoFactorForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Two-Factor Authentication')
                    ->description('Add additional security to your account using two-factor authentication.')
                    ->schema([
                        Toggle::make('two_factor_enabled')
                            ->label('Enable Two-Factor Authentication')
                            ->helperText('When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication.')
                            ->live(),
                    ]),
            ])
            ->statePath('twoFactorData');
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

    public function updateTwoFactor(): void
    {
        $enabled = $this->twoFactorData['two_factor_enabled'] ?? false;
        $user = auth()->user();

        if ($enabled) {
            $user->update([
                'two_factor_secret' => Str::random(32),
                'two_factor_confirmed_at' => now(),
            ]);
        } else {
            $user->update([
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]);
        }

        Notification::make()
            ->success()
            ->title($enabled ? 'Two-factor authentication enabled' : 'Two-factor authentication disabled')
            ->send();
    }
}
