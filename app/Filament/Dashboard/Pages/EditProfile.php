<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\ReservationSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

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
        ];

        $settings = ReservationSetting::getInstance();
        $this->businessData = [
            'business_name' => $settings->business_name,
            'business_address' => $settings->business_address,
            'business_phone' => $settings->business_phone,
            'business_hours' => $settings->business_hours,
        ];
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'passwordForm',
            'businessForm',
        ];
    }

    public function profileForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile Information')
                    ->description('Update your account\'s profile information and email address.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->avatar()
                            ->imageEditor()
                            ->directory('avatars'),
                    ])
                    ->columns(2),
            ])
            ->statePath('profileData')
            ->model(auth()->user());
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Update Password')
                    ->description('Ensure your account is using a long, random password to stay secure.')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        Forms\Components\TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->same('password_confirmation'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->autocomplete('new-password'),
                    ]),
            ])
            ->statePath('passwordData');
    }

    public function businessForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Business Details')
                    ->description('Manage your business information and operating hours.')
                    ->schema([
                        Forms\Components\TextInput::make('business_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Textarea::make('business_address')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('business_hours')
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
