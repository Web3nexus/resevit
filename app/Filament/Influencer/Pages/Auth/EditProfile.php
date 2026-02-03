<?php

namespace App\Filament\Influencer\Pages\Auth;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\View;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class EditProfile extends BaseEditProfile implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.influencer.pages.auth.edit-profile';

    public ?array $twoFactorData = [];

    protected function getSchemas(): array
    {
        return [
            'form',
            'twoFactorForm',
        ];
    }

    public function twoFactorForm(Schema $schema): Schema
    {
        $user = auth()->user();
        $isTwoFactorEnabled = $user->hasTwoFactorEnabled();

        return $schema
            ->schema([
                Section::make('Two-Factor Authentication')
                    ->description('Add additional security to your account using two-factor authentication.')
                    ->schema([
                        View::make('filament.influencer.pages.auth.two-factor-status')
                            ->viewData([
                                'enabled' => $isTwoFactorEnabled,
                            ]),

                        Actions::make([
                            Action::make('enable')
                                ->label('Enable Two-Factor Authentication')
                                ->button()
                                ->color('primary')
                                ->visible(!$isTwoFactorEnabled)
                                ->action(function () use ($user) {
                                    $user->enableTwoFactorAuthentication();
                                })
                                ->modalHeading('Setup Two-Factor Authentication')
                                ->modalContent(function () use ($user) {
                                    if (!$user->two_factor_secret) {
                                        $user->enableTwoFactorAuthentication();
                                    }
                                    return view('filament.influencer.pages.auth.two-factor-modal', [
                                        'qrCodeUrl' => $user->getTwoFactorQrCodeUrl(),
                                        'secret' => decrypt($user->two_factor_secret),
                                    ]);
                                })
                                ->modalSubmitActionLabel('Confirm')
                                ->form([
                                    TextInput::make('code')
                                        ->label('Verification Code')
                                        ->placeholder('Enter the code from your authenticator app')
                                        ->required()
                                        ->numeric(),
                                ])
                                ->action(function (array $data) use ($user) {
                                    if ($user->confirmTwoFactorAuthentication($data['code'])) {
                                        Notification::make()->title('Two-factor authentication enabled')->success()->send();
                                        $this->dispatch('open-recovery-codes-modal');
                                    } else {
                                        Notification::make()->title('Invalid verification code')->danger()->send();
                                        $user->disableTwoFactorAuthentication();
                                    }
                                }),

                            Action::make('disable')
                                ->label('Disable Two-Factor Authentication')
                                ->button()
                                ->color('danger')
                                ->visible($isTwoFactorEnabled)
                                ->requiresConfirmation()
                                ->action(function () use ($user) {
                                    $user->disableTwoFactorAuthentication();
                                    Notification::make()->title('Two-factor authentication disabled')->success()->send();
                                }),

                            Action::make('showRecoveryCodes')
                                ->label('Show Recovery Codes')
                                ->button()
                                ->color('gray')
                                ->visible($isTwoFactorEnabled)
                                ->modalHeading('Recovery Codes')
                                ->modalContent(function () use ($user) {
                                    return view('filament.influencer.pages.auth.recovery-codes-modal', [
                                        'codes' => $user->getTwoFactorRecoveryCodes(),
                                    ]);
                                })
                                ->modalSubmitAction(false),
                        ]),
                    ]),
            ])
            ->statePath('twoFactorData');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }
}
