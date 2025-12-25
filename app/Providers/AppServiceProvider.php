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
        // Register model observers
        \App\Models\Reservation::observe(\App\Observers\ReservationObserver::class);

        // Register Tenant Policies
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Reservation::class, \App\Policies\ReservationPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\MenuItem::class, \App\Policies\MenuItemPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Category::class, \App\Policies\CategoryPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\MarketingCampaign::class, \App\Policies\MarketingCampaignPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\MarketingTemplate::class, \App\Policies\MarketingTemplatePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\SocialAccount::class, \App\Policies\SocialAccountPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Table::class, \App\Policies\TablePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Room::class, \App\Policies\RoomPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Addon::class, \App\Policies\AddonPolicy::class);

        // Register policies
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\CalendarEvent::class,
            \App\Policies\CalendarEventPolicy::class
        );

        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Order::class,
            \App\Policies\OrderPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Reservation::class,
            \App\Policies\ReservationPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\MenuItem::class,
            \App\Policies\MenuItemPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Category::class,
            \App\Policies\CategoryPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\MarketingCampaign::class,
            \App\Policies\MarketingCampaignPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\MarketingTemplate::class,
            \App\Policies\MarketingTemplatePolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\SocialAccount::class,
            \App\Policies\SocialAccountPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Table::class,
            \App\Policies\TablePolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Room::class,
            \App\Policies\RoomPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Addon::class,
            \App\Policies\AddonPolicy::class
        );

        // Register global impersonation banner
        // Register global impersonation banner
        // \Filament\Support\Facades\FilamentView::registerRenderHook(
        //     \Filament\View\PanelsRenderHook::BODY_END,
        //     fn(): string => \Illuminate\Support\Facades\View::make('components.impersonation-banner')->render(),
        // );



        // Register Calendar Widget manually as it's in a custom location
        // \Livewire\Livewire::component('app.filament.dashboard.components.calendar-widget', \App\Filament\Dashboard\Components\CalendarWidget::class);
    }
}
