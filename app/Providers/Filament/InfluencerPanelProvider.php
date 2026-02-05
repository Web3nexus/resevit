<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class InfluencerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('influencer')
            ->path('influencer')
            ->login(\App\Filament\Influencer\Pages\Auth\Login::class)
            ->registration(\App\Filament\Influencer\Pages\Auth\Register::class)
            ->profile(\App\Filament\Influencer\Pages\Auth\EditProfile::class)
            ->authGuard('influencer')
            ->viteTheme('resources/css/app.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Influencer/Resources'), for: 'App\Filament\Influencer\Resources')
            ->discoverPages(in: app_path('Filament/Influencer/Pages'), for: 'App\Filament\Influencer\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Influencer/Widgets'), for: 'App\Filament\Influencer\Widgets')
            ->widgets([
                // Default widgets removed for a cleaner dashboard
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandLogo(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->logo_path ? \App\Helpers\StorageHelper::getUrl($setting->logo_path) : null)
            ->darkModeBrandLogo(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->logo_dark_path ? \App\Helpers\StorageHelper::getUrl($setting->logo_dark_path) : null)
            ->favicon(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->favicon_path ? \App\Helpers\StorageHelper::getUrl($setting->favicon_path) : null);
    }
}
