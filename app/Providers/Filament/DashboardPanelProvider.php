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

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->registration(\App\Filament\Pages\Auth\Register::class)
            ->databaseNotifications()
            // ->databaseNotificationsPolling('30s')
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0B132B'),
                'gold' => \Filament\Support\Colors\Color::hex('#F1C40F'),
            ])
            ->darkMode(true)
            ->viteTheme('resources/css/app.css')
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\\Filament\\Dashboard\\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\\Filament\\Dashboard\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dashboard/Widgets'), for: 'App\\Filament\\Dashboard\\Widgets')
            ->navigationGroups([
                'Calendar',
                'Reservations',
                'Menu Management',
                'Staff Management',
            ])
            // ->spa()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                \App\Http\Middleware\SetLocale::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                \App\Http\Middleware\FilamentTenantGate::class,
                \App\Http\Middleware\SetTenantTimezone::class,
                DispatchServingFilamentEvent::class,
            ])
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->label('Account')
                    ->url(fn() => \App\Filament\Dashboard\Pages\EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
                \Filament\Navigation\MenuItem::make()
                    ->label('System Settings')
                    ->url(fn() => route('filament.dashboard.resources.system-settings.edit'))
                    ->icon('heroicon-o-cog-6-tooth'),
                \Filament\Navigation\MenuItem::make()
                    ->label('Subscription & Plans')
                    ->url(fn() => \App\Filament\Dashboard\Pages\Subscription::getUrl())
                    ->icon('heroicon-o-credit-card'),
                \Filament\Navigation\MenuItem::make()
                    ->label('Help center')
                    ->url('#')
                    ->icon('heroicon-o-question-mark-circle'),
            ])
            ->sidebarWidth('250px')
            ->sidebarCollapsibleOnDesktop()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandLogo(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->logo_path ? \Illuminate\Support\Facades\Storage::url($setting->logo_path) : null)
            ->favicon(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->favicon_path ? \Illuminate\Support\Facades\Storage::url($setting->favicon_path) : null);
        // ->renderHook(
        //     'panels::user-menu.before',
        //     fn() => view('filament.components.language-switcher-hook')
        // );
    }
}
