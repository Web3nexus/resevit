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
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0B132B'),
                'gold' => \Filament\Support\Colors\Color::hex('#F1C40F'),
            ])
            ->darkMode(false)
            ->viteTheme('resources/css/app.css')
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\\Filament\\Dashboard\\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\\Filament\\Dashboard\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dashboard/Widgets'), for: 'App\\Filament\\Dashboard\\Widgets')
            ->widgets([])
            ->navigationGroups([
                'Calendar',
                'Reservations',
                'Staff Management',
            ])
            ->spa()
            ->middleware([
                \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
                \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
                \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
                \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetTenantTimezone::class, // Set timezone after tenancy init
            ])
            ->userMenuItems([
                'account' => \Filament\Actions\Action::make('account')
                    ->label('Account')
                    ->url(fn() => \App\Filament\Dashboard\Pages\EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
                'settings' => \Filament\Actions\Action::make('settings')
                    ->label('System Settings')
                    ->url(fn() => route('filament.dashboard.resources.system-settings.edit'))
                    ->icon('heroicon-o-cog-6-tooth'),
                'plans' => \Filament\Actions\Action::make('plans')
                    ->label('Plans')
                    ->url('#')
                    ->icon('heroicon-o-credit-card'),
                'help' => \Filament\Actions\Action::make('help')
                    ->label('Help center')
                    ->url('#')
                    ->icon('heroicon-o-question-mark-circle'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
