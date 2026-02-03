<?php

namespace App\Filament\Customer\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Filament\Models\Contracts\FilamentUser;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();
        } catch (\Throwable $e) {
            return null;
        }

        if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        // 2FA Check
        if (method_exists($user, 'hasTwoFactorEnabled') && $user->hasTwoFactorEnabled()) {
            Filament::auth()->logout();
            session()->put('login.id', $user->getKey());
            session()->put('login.remember', $data['remember'] ?? false);

            throw new HttpResponseException(redirect()->route('filament.customer.auth.two-factor-challenge'));
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
