<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Components\View;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Facades\Hash;
use App\Services\TenantCreatorService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Illuminate\Auth\Events\Registered;
use Filament\Facades\Filament;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Illuminate\Support\Facades\Log;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;


class Register extends BaseRegister
{
    use InteractsWithFormActions;

    protected ?string $redirectUrl = null;

    protected static string $layout = 'filament-panels::components.layout.base';

    protected string $view = 'filament.pages.auth.register';

    public function mount(): void
    {
        Log::info('Register component mounted');
        parent::mount();
    }

    public function getHeading(): string|Htmlable
    {
        return "We're excited you're here!";
    }

    public function getSubheading(): string|Htmlable|null
    {
        return "Start by creating an account.";
    }

    // Copy logic from RegisterTenant.php
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Credentials')
                        ->schema([
                            TextInput::make('email')
                                // ->label('Email Address')
                                ->hiddenLabel()
                                ->placeholder('Email Address')
                                ->prefixIcon('heroicon-o-envelope')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(table: fn(Get $get) => match ($get('role')) {
                                    'investor' => 'investors',
                                    'customer' => 'customers',
                                    default => 'users',
                                }, column: 'email'),

                            TextInput::make('password')
                                // ->label('Password')
                                ->hiddenLabel()
                                ->placeholder('Password')
                                ->prefixIcon('heroicon-o-lock-closed')
                                ->password()
                                ->revealable()
                                ->required()
                                ->minLength(8),

                            Select::make('role')
                                // ->label('Role')
                                ->hiddenLabel()
                                ->placeholder('Select your role')
                                ->prefixIcon('heroicon-o-shield-check')
                                ->options([
                                    'business_owner' => 'Business Owner',
                                    'customer' => 'Customer',
                                    'investor' => 'Investor',
                                ])
                                ->default('business_owner')
                                ->native(true)
                                ->required()
                                ->live(), // Keep live for conditional steps
                        ]),

                    Wizard\Step::make('Personal Details')
                        ->schema([
                            TextInput::make('name')
                                // ->label('Full Name')
                                ->hiddenLabel()
                                ->placeholder('Full Name')
                                ->prefixIcon('heroicon-o-user')
                                ->required()
                                ->maxLength(255),

                            Select::make('country')
                                // ->label('Country')
                                ->hiddenLabel()
                                ->placeholder('Select Country')
                                ->prefixIcon('heroicon-o-globe-alt')
                                ->options(self::getCountries())
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $phoneCode = self::getPhoneCode($state);
                                    $set('phone_code', $phoneCode);
                                })
                                ->required(),

                            TextInput::make('mobile')
                                // ->label('Mobile Number')
                                ->hiddenLabel()
                                ->placeholder('Mobile Number')
                                ->prefix(fn(Get $get) => self::getPhoneCode($get('country')) ?: '+')
                                ->tel()
                                ->required()
                                ->maxLength(255),
                        ]),

                    Wizard\Step::make('Business Details')
                        ->visible(fn(Get $get) => $get('role') === 'business_owner')
                        ->schema([
                            TextInput::make('restaurant_name')
                                // ->label('Restaurant Name')
                                ->hiddenLabel()
                                ->placeholder('Restaurant Name')
                                ->prefixIcon('heroicon-o-building-storefront')
                                ->required()
                                ->maxLength(255),

                            Select::make('staff_count')
                                ->label('Number of Staff')
                                ->placeholder('Select Staff Range')
                                ->prefixIcon('heroicon-o-users')
                                ->options(fn() => \App\Models\StaffRange::pluck('label', 'range'))
                                ->required(),
                        ]),

                    Wizard\Step::make('Terms')
                        ->schema([
                            Checkbox::make('terms_accepted')
                                ->label('I agree to the terms and conditions and consent to receive updates.')
                                ->required()
                                ->accepted()
                                ->validationMessages([
                                    'accepted' => 'You must agree to the terms to register.',
                                ]),
                        ])
                ])
                    // ->submitAction(
                    //     Action::make('register')
                    //         ->label('Register')
                    //         ->extraAttributes([
                    //             'class' => 'fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center gap-1.5 rounded-lg border border-transparent bg-custom-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition duration-75 focus-visible:ring-2 focus-visible:ring-custom-500/50 disabled:pointer-events-none disabled:opacity-70 dark:bg-custom-500 dark:focus-visible:ring-custom-400/50 grid w-full',
                    //             'type' => 'submit',
                    //         ])

                    // )
                    // ✅ Ensure Next / Previous work
                    ->hiddenHeader()

                    // ✅ Ensure Next / Previous work
                    ->previousAction(
                        fn(Action $action) =>
                        $action->extraAttributes(['type' => 'button'])
                    )
                    ->nextAction(
                        fn(Action $action) =>
                        $action->extraAttributes(['type' => 'button'])
                    )

                    // ✅ ONLY customize the final submit button
                    ->submitAction(
                        new \Illuminate\Support\HtmlString(
                            '<button type="submit" wire:loading.attr="disabled" wire:target="register"
            class="fi-btn fi-btn-size-md w-full rounded-lg bg-custom-600 text-gold-500 font-bold flex items-center justify-center gap-2">
            <span wire:loading.remove wire:target="register">Register</span>
            <span wire:loading wire:target="register" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-gold-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating your restaurant...
            </span>
        </button>'
                        )
                    ),

                View::make('filament.pages.auth.partials.social-buttons'),
                View::make('filament.pages.auth.partials.login-link'),

            ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $role = $data['role'] ?? 'business_owner';

        Log::info('Registration attempt', ['role' => $role, 'data' => $data]);

        if ($role === 'business_owner') {
            Log::info('Registering business owner');
            $user = $this->handleRegistration($data);

            event(new Registered($user));
            // Email verification disabled
            // $this->sendEmailVerificationNotification($user);

            Filament::auth()->login($user);

            if ($this->redirectUrl) {
                $this->redirect($this->redirectUrl);
            } else {
                $this->redirect(filament()->getUrl());
            }

            return null;
        }

        if ($role === 'customer') {
            $this->registerCustomer($data);

            Notification::make()
                ->title('Registration successful!')
                ->body('Please log in to your account.')
                ->success()
                ->send();

            if ($this->redirectUrl) {
                $this->redirect($this->redirectUrl);
            }

            return null;
        }

        if ($role === 'investor') {
            $this->registerInvestor($data);

            Notification::make()
                ->title('Application Submitted')
                ->body('Please log in to your dashboard.')
                ->success()
                ->send();

            if ($this->redirectUrl) {
                $this->redirect($this->redirectUrl);
            }

            return null;
        }

        return null;
    }

    protected function handleRegistration(array $data): Model
    {
        $role = $data['role'] ?? 'business_owner';

        if ($role === 'business_owner') {
            return $this->registerBusinessOwner($data);
        } elseif ($role === 'customer') {
            return $this->registerCustomer($data);
        } elseif ($role === 'investor') {
            return $this->registerInvestor($data);
        }

        // Fallback (shouldn't happen due to required rule)
        throw new \Exception('Invalid role selected');
    }

    protected function registerBusinessOwner(array $data)
    {
        Log::info('Starting registerBusinessOwner', ['restaurant_name' => $data['restaurant_name']]);
        $slug = \Illuminate\Support\Str::slug($data['restaurant_name']);
        $original = $slug;
        $i = 1;

        while (\App\Models\Tenant::where('slug', $slug)->exists()) {
            $slug = $original . $i++;
        }

        $user = \App\Models\LandlordUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'mobile' => $data['mobile'] ?? null,
            'country' => $data['country'] ?? null,
            'terms_accepted' => $data['terms_accepted'] ?? false,
            'newsletter_subscribed' => $data['newsletter_subscribed'] ?? false,
        ]);

        try {
            $extraData = [
                // Staff count is business data, pass it here
                'staff_count' => $data['staff_count'] ?? null,
                // mobile/country moved to user, so not passing them to tenant anymore
            ];

            $tenant = app(TenantCreatorService::class)
                ->createTenant($user, $data['restaurant_name'], $slug, $extraData);

            DB::purge('tenant');
            DB::setDefaultConnection('landlord');

            // Hook for automatic login after this method returns? 
            // Filament Register expects a Model returned which it logs in.
            // LandlordUser is Authenticatable, so returning $user works.

            // We need to store the redirect URL in session or handle it in specific redirect hook
            $this->redirectUrl = tenant_route(
                $tenant->domains->first()->domain,
                'filament.dashboard.auth.login'
            );

            return $user;
        } catch (\Throwable $e) {
            Log::error('Registration failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $user->delete();
            throw $e;
        }
    }

    protected function registerCustomer(array $data)
    {
        $customer = \App\Models\Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'terms_accepted' => $data['terms_accepted'] ?? false,
            'newsletter_subscribed' => $data['newsletter_subscribed'] ?? false,
        ]);

        // Assign role with correct guard
        $customer->assignRole('customer', 'customer');

        // Store intended redirect
        $this->redirectUrl = route('customer.login');

        return $customer;
    }

    protected function registerInvestor(array $data)
    {
        $investor = \App\Models\Investor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'terms_accepted' => $data['terms_accepted'] ?? false,
            'newsletter_subscribed' => $data['newsletter_subscribed'] ?? false,
        ]);

        // Assign role with correct guard
        $investor->assignRole('investor', 'investor');

        $this->redirectUrl = route('investor.login');

        return $investor;
    }

    protected static function getCountries(): array
    {
        return [
            'Afghanistan' => 'Afghanistan',
            'Albania' => 'Albania',
            'Algeria' => 'Algeria',
            'Argentina' => 'Argentina',
            'Australia' => 'Australia',
            'Austria' => 'Austria',
            'Bangladesh' => 'Bangladesh',
            'Belgium' => 'Belgium',
            'Brazil' => 'Brazil',
            'Canada' => 'Canada',
            'China' => 'China',
            'Colombia' => 'Colombia',
            'Denmark' => 'Denmark',
            'Egypt' => 'Egypt',
            'Finland' => 'Finland',
            'France' => 'France',
            'Germany' => 'Germany',
            'Ghana' => 'Ghana',
            'Greece' => 'Greece',
            'India' => 'India',
            'Indonesia' => 'Indonesia',
            'Ireland' => 'Ireland',
            'Italy' => 'Italy',
            'Japan' => 'Japan',
            'Kenya' => 'Kenya',
            'Malaysia' => 'Malaysia',
            'Mexico' => 'Mexico',
            'Netherlands' => 'Netherlands',
            'New Zealand' => 'New Zealand',
            'Nigeria' => 'Nigeria',
            'Norway' => 'Norway',
            'Pakistan' => 'Pakistan',
            'Philippines' => 'Philippines',
            'Poland' => 'Poland',
            'Portugal' => 'Portugal',
            'Russia' => 'Russia',
            'Saudi Arabia' => 'Saudi Arabia',
            'Singapore' => 'Singapore',
            'South Africa' => 'South Africa',
            'South Korea' => 'South Korea',
            'Spain' => 'Spain',
            'Sweden' => 'Sweden',
            'Switzerland' => 'Switzerland',
            'Thailand' => 'Thailand',
            'Turkey' => 'Turkey',
            'Uganda' => 'Uganda',
            'Ukraine' => 'Ukraine',
            'United Arab Emirates' => 'United Arab Emirates',
            'United Kingdom' => 'United Kingdom',
            'United States' => 'United States',
            'Vietnam' => 'Vietnam',
        ];
    }

    protected static function getPhoneCode(?string $country): ?string
    {
        $phoneCodes = [
            'Afghanistan' => '+93',
            'Albania' => '+355',
            'Algeria' => '+213',
            'Argentina' => '+54',
            'Australia' => '+61',
            'Austria' => '+43',
            'Bangladesh' => '+880',
            'Belgium' => '+32',
            'Brazil' => '+55',
            'Canada' => '+1',
            'China' => '+86',
            'Colombia' => '+57',
            'Denmark' => '+45',
            'Egypt' => '+20',
            'Finland' => '+358',
            'France' => '+33',
            'Germany' => '+49',
            'Ghana' => '+233',
            'Greece' => '+30',
            'India' => '+91',
            'Indonesia' => '+62',
            'Ireland' => '+353',
            'Italy' => '+39',
            'Japan' => '+81',
            'Kenya' => '+254',
            'Malaysia' => '+60',
            'Mexico' => '+52',
            'Netherlands' => '+31',
            'New Zealand' => '+64',
            'Nigeria' => '+234',
            'Norway' => '+47',
            'Pakistan' => '+92',
            'Philippines' => '+63',
            'Poland' => '+48',
            'Portugal' => '+351',
            'Russia' => '+7',
            'Saudi Arabia' => '+966',
            'Singapore' => '+65',
            'South Africa' => '+27',
            'South Korea' => '+82',
            'Spain' => '+34',
            'Sweden' => '+46',
            'Switzerland' => '+41',
            'Thailand' => '+66',
            'Turkey' => '+90',
            'Uganda' => '+256',
            'Ukraine' => '+380',
            'United Arab Emirates' => '+971',
            'United Kingdom' => '+44',
            'United States' => '+1',
            'Vietnam' => '+84',
        ];

        return $phoneCodes[$country] ?? null;
    }
}
