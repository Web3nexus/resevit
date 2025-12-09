<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the Filament Investor panel provider so the panel is available without editing
        // config/app.php. If you prefer to add it to the providers array, you can do that instead.
        if (class_exists(\App\Providers\Filament\InvestorPanelProvider::class)) {
            $this->app->register(\App\Providers\Filament\InvestorPanelProvider::class);
        }
        if (class_exists(\App\Providers\Filament\CustomerPanelProvider::class)) {
            $this->app->register(\App\Providers\Filament\CustomerPanelProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
