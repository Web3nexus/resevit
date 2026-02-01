<?php

namespace App\Filament\Pages\Auth;

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

    /**
     * Get the form schema. (Custom fields if needed, or just standard)
     * For now, standard is fine, but we can style them here if we want overrides.
     */
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

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
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
            $email = $credentials['email']; // Define email early for logging

            // 1. Try Local Login first
            \Illuminate\Support\Facades\Log::info("DEBUG: Attempting local login for $email on connection: ".(new \App\Models\User)->getConnectionName());

            if (\Filament\Facades\Filament::auth()->attempt($credentials, $remember)) {
                $user = \Filament\Facades\Filament::auth()->user();
                \Illuminate\Support\Facades\Log::info('LOGIN: Local login success for '.$email.'. User ID: '.$user->id.' on connection: '.$user->getConnectionName());
                // session()->regenerate(); // default attempt() already does this

                // Explicitly redirect to dashboard to prevent 'intended' loop back to login
                return new class implements \Filament\Auth\Http\Responses\Contracts\LoginResponse, \Illuminate\Contracts\Support\Responsable
                {
                    public function toResponse($request)
                    {
                        return redirect()->to('/dashboard');
                    }
                };
            }
            \Illuminate\Support\Facades\Log::info('LOGIN: Local login failed for '.$email.'. Trying Landlord.');

            // If we are already in a tenant context, stop here.
            // We don't want to check Landlord credentials because that leads to a redirect loop (redirecting to self).
            if (function_exists('tenant') && tenant()) {
                $this->throwFailureValidationException();
            }

            // 2. Try Landlord Login (Central User) and Redirect to Tenant
            // Only proceed if we are potentially on the central portal (conceptually)
            $password = $credentials['password'];

            $landlordUser = \App\Models\LandlordUser::where('email', '=', $email, 'and')->first();

            if ($landlordUser && \Illuminate\Support\Facades\Hash::check($password, $landlordUser->password)) {
                \Illuminate\Support\Facades\Log::info('LOGIN: Landlord user found: '.$landlordUser->id);
                // User credentials are valid for a Landlord User.
                // Find their tenant.
                $tenant = \App\Models\Tenant::where('owner_user_id', '=', $landlordUser->id, 'and')->first();

                if ($tenant) {
                    \Illuminate\Support\Facades\Log::info('LOGIN: Tenant found: '.$tenant->id);

                    $domain = $tenant->domains->first()?->domain;

                    if ($domain) {
                        // Redirect to the tenant's login page, just like Register.php
                        $url = tenant_route($domain, 'filament.dashboard.auth.login');
                        \Illuminate\Support\Facades\Log::info('LOGIN: Redirecting to '.$url);

                        return new class($url) implements \Filament\Auth\Http\Responses\Contracts\LoginResponse, \Illuminate\Contracts\Support\Responsable
                        {
                            public function __construct(protected string $url) {}

                            public function toResponse($request)
                            {
                                return redirect()->to($this->url);
                            }
                        };
                    } else {
                        \Illuminate\Support\Facades\Log::error('LOGIN: No domain found for tenant.');
                    }
                } else {
                    \Illuminate\Support\Facades\Log::error('LOGIN: No tenant found for landlord user.');
                }
            } else {
                \Illuminate\Support\Facades\Log::info('LOGIN: Landlord auth failed or user not found.');
            }

            $this->throwFailureValidationException();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('LOGIN ERROR: '.$e->getMessage());
            throw $e;
        }
    }
}
