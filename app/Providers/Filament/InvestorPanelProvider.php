<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class InvestorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('invest')
            ->path('invest')
            ->authGuard('investor')
            // enable the built-in login form
            ->login()
            // NOTE: Filament's registration page is enabled by adding the Register page
            // (most Filament installs will provide this). We keep login + registration support.
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0B132B'),
            ])
            // discover resources/pages/widgets in the Invest namespace
            ->discoverResources(in: app_path('Filament/Invest/Resources'), for: 'App\\Filament\\Invest\\Resources')
            ->discoverPages(in: app_path('Filament/Invest/Pages'), for: 'App\\Filament\\Invest\\Pages')
            ->pages([
                // default dashboard placeholder; projects can add real pages under App\Filament\Invest\Pages
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Invest/Widgets'), for: 'App\\Filament\\Invest\\Widgets')
            ->widgets([
                // placeholder widgets
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            // Use Filament's Authenticate middleware and a custom EnsureInvestorRole middleware
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureInvestorRole::class,
            ])
            // Navigation is provided by discovered resources/pages. Add Filament resources/pages under
            // app/Filament/Invest/* (for example: Investments, Portfolio, Wallet) to populate navigation.
            ;
    }
}
