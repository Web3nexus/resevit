<?php

namespace App\Filament\Dashboard\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Filament\Models\Contracts\FilamentUser;
use Filament\Facades\Filament;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;

class Login extends BaseLogin implements HasSchemas
{
    use InteractsWithFormActions;
    use InteractsWithSchemas;
    use WithRateLimiting;

    protected function getSchemas(): array
    {
        return [
            'form',
        ];
    }

    protected function getFormActions(): array
    {
        return parent::getFormActions();
    }

    protected static string $layout = 'filament-panels::components.layout.base';

    protected string $view = 'filament.pages.auth.login';

    public function getHeading(): string|Htmlable
    {
        return 'Hey there!';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Ready to pick up where you left off?';
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

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        try {
            $data = $this->form->getState();
            $credentials = $this->getCredentialsFromFormData($data);
            $remember = $data['remember'] ?? false;
            $email = $credentials['email'];

            \Illuminate\Support\Facades\Log::info("DEBUG: Attempting local login for $email on connection: " . (new \App\Models\User)->getConnectionName());

            if (\Filament\Facades\Filament::auth()->attempt($credentials, $remember)) {
                $user = \Filament\Facades\Filament::auth()->user();

                if (
                    ($user instanceof FilamentUser) &&
                    (!$user->canAccessPanel(Filament::getCurrentPanel()))
                ) {
                    Filament::auth()->logout();
                    $this->throwFailureValidationException();
                }

                \Illuminate\Support\Facades\Log::info('LOGIN: Local login success for ' . $email);

                // 2FA Check
                if (method_exists($user, 'hasTwoFactorEnabled') && $user->hasTwoFactorEnabled()) {
                    Filament::auth()->logout();
                    session()->put('login.id', $user->getKey());
                    session()->put('login.remember', $data['remember'] ?? false);

                    throw new HttpResponseException(redirect()->route('filament.dashboard.auth.two-factor-challenge'));
                }

                return new class implements LoginResponse, \Illuminate\Contracts\Support\Responsable {
                    public function toResponse($request)
                    {
                        return redirect()->to('/dashboard');
                    }
                };
            }

            \Illuminate\Support\Facades\Log::info('LOGIN: Local login failed for ' . $email . '. Trying Landlord.');

            if (function_exists('tenant') && tenant()) {
                $this->throwFailureValidationException();
            }

            $password = $credentials['password'];
            $landlordUser = \App\Models\LandlordUser::where('email', '=', $email, 'and')->first();

            if ($landlordUser && \Illuminate\Support\Facades\Hash::check($password, $landlordUser->password)) {
                $tenant = \App\Models\Tenant::where('owner_user_id', '=', $landlordUser->id, 'and')->first();

                if ($tenant) {
                    $domain = $tenant->domains->first()?->domain;

                    if ($domain) {
                        $url = tenant_route($domain, 'filament.dashboard.auth.login');
                        return new class ($url) implements LoginResponse, \Illuminate\Contracts\Support\Responsable {
                            public function __construct(protected string $url)
                            {}

                            public function toResponse($request)
                            {
                                return redirect()->to($this->url);
                            }
                        };
                    }
                }
            }

            $this->throwFailureValidationException();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('LOGIN ERROR: ' . $e->getMessage());
            throw $e;
        }
    }
}
