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

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('customer')
            ->authGuard('customer')
            ->databaseNotifications()
            ->viteTheme('resources/css/app.css')
            ->login(\App\Filament\Customer\Pages\Auth\Login::class)
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0B132B'),
            ])
            ->discoverResources(in: app_path('Filament/Customer/Resources'), for: 'App\\Filament\\Customer\\Resources')
            ->discoverPages(in: app_path('Filament/Customer/Pages'), for: 'App\\Filament\\Customer\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->userMenuItems([
                'account' => \Filament\Actions\Action::make('account')
                    ->label('Account')
                    ->url(fn() => \App\Filament\Customer\Pages\EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            ->spa()
            ->discoverWidgets(in: app_path('Filament/Customer/Widgets'), for: 'App\\Filament\\Customer\\Widgets')
            ->widgets([
                // placeholder widgets
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                \App\Http\Middleware\SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->sidebarWidth('250px')
            ->sidebarCollapsibleOnDesktop()
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureCustomerRole::class,
            ])
            ->renderHook(
                'panels::user-menu.before',
                fn() => view('filament.components.language-switcher-hook')
            )
            ->brandLogo(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->logo_path ? \App\Helpers\StorageHelper::getUrl($setting->logo_path) : null)
            ->favicon(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->favicon_path ? \App\Helpers\StorageHelper::getUrl($setting->favicon_path) : null);
    }
}
