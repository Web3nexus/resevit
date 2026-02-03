<?php

namespace App\Filament\Securegate\Pages\Auth;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\Action as PageAction;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use App\Filament\Securegate\Pages\Auth\Login;

class TwoFactorChallenge extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.securegate.pages.auth.two-factor-challenge';

    protected static string $layout = 'filament-panels::components.layout.base';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public bool $usingRecoveryCode = false;

    public function mount(): void
    {
        if (!session()->has('login.id')) {
            redirect()->route('filament.securegate.auth.login');
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label($this->usingRecoveryCode ? 'Recovery Code' : 'Authentication Code')
                    ->placeholder($this->usingRecoveryCode ? 'Enter recovery code' : 'Enter generated code')
                    ->required()
                    ->autofocus()
                    ->extraInputAttributes(['autocomplete' => 'one-time-code']),
            ])
            ->statePath('data');
    }

    public function authenticate(): void
    {
        $data = $this->form->getState();
        $code = $data['code'];

        $userId = session('login.id');
        // Find admin because we are in Securegate (Super Admin)
        $user = Admin::find($userId);

        if (!$user) {
            redirect()->route('filament.securegate.auth.login');
            return;
        }

        $isValid = false;

        if ($this->usingRecoveryCode) {
            $recoveryCodes = $user->getTwoFactorRecoveryCodes();
            if (in_array($code, $recoveryCodes)) {
                $isValid = true;
                // Remove used code
                $user->forceFill([
                    'two_factor_recovery_codes' => encrypt(json_encode(array_diff($recoveryCodes, [$code])))
                ])->save();
            }
        } else {
            $isValid = $user->verifyTwoFactorAuthentication($code);
        }

        if ($isValid) {
            \Filament\Facades\Filament::auth()->login($user, session('login.remember', false));
            session()->forget(['login.id', 'login.remember']);
            session()->regenerate();

            $this->redirect(config('filament.securegate.home_url', '/securegate'));
        } else {
            Notification::make()
                ->title('Invalid code')
                ->danger()
                ->send();

            // Throw validation exception to show error on field
            throw ValidationException::withMessages([
                'data.code' => 'The provided code was invalid.',
            ]);
        }
    }

    public function toggleRecoveryCode(): void
    {
        $this->usingRecoveryCode = !$this->usingRecoveryCode;
        $this->form->fill(); // Clear form
    }

    public function getHeading(): string
    {
        return 'Two-Factor Authentication';
    }
}
