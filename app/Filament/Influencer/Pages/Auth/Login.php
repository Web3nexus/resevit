<?php

namespace App\Filament\Influencer\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

use Filament\Facades\Filament;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Filament\Models\Contracts\FilamentUser;

class Login extends BaseLogin implements HasSchemas
{
    use InteractsWithSchemas;

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

            throw new HttpResponseException(redirect()->route('filament.influencer.auth.two-factor-challenge'));
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function getSchemas(): array
    {
        return [
            'form',
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Welcome back, Partner!';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Log in to your influencer dashboard.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email address')
            ->placeholder('Your email')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->prefixIcon('heroicon-o-envelope');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->placeholder('Your password')
            ->password()
            ->revealable()
            ->required()
            ->prefixIcon('heroicon-o-lock-closed');
    }
}
