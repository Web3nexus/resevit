<?php

namespace App\Filament\Securegate\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Validation\ValidationException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Illuminate\Http\Exceptions\HttpResponseException;

class Login extends BaseLogin
{
    use WithRateLimiting;

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

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

            throw new HttpResponseException(redirect()->route('filament.securegate.pages.auth.two-factor-challenge'));
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
