<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use App\Filament\Securegate\Pages\EditProfile;
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

class SecuregatePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('securegate')
            ->path('securegate')
            ->authGuard('securegate')
            ->databaseNotifications()
            ->viteTheme('resources/css/app.css')
            ->login()
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0B132B'),
            ])
            ->discoverResources(in: app_path('Filament/Securegate/Resources'), for: 'App\\Filament\\Securegate\\Resources')
            ->discoverPages(in: app_path('Filament/Securegate/Pages'), for: 'App\\Filament\\Securegate\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Securegate/Widgets'), for: 'App\\Filament\\Securegate\\Widgets')
            ->widgets([
                \App\Filament\Securegate\Widgets\UptimeStatsWidget::class,
                \App\Filament\Securegate\Widgets\ServicesStatusWidget::class,
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
            ->spa()
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->label('Edit Profile')
                    ->url(fn(): string => EditProfile::getUrl())
                    ->icon('heroicon-m-user-circle'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandLogo(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->logo_path ? \Illuminate\Support\Facades\Storage::url($setting->logo_path) : null)
            ->favicon(fn() => ($setting = \App\Models\PlatformSetting::current()) && $setting->favicon_path ? \Illuminate\Support\Facades\Storage::url($setting->favicon_path) : null)
            ->navigationGroups([
                'Dashboard',
                'Marketing Tools',
                'Internal Users',
                'External Users',
                'AI Management',
                'Smart Access',
                'Landing Management',
                'Platform Settings',
                'System Management',
            ])
            ->sidebarWidth('250px')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                'panels::user-menu.before',
                fn() => view('filament.components.language-switcher-hook')
            );


    }
}
