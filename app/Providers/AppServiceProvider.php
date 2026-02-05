<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

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
        // Register custom commands explicitly
        $this->commands([
            \App\Console\Commands\SmartTenantsMigrate::class,
            \App\Console\Commands\FixMissingBranchesTable::class,
            \App\Console\Commands\ForceRepairTenantDatabase::class,
        ]);

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
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Inventory::class, \App\Policies\InventoryPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Promotion::class, \App\Policies\PromotionPolicy::class);

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

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn(): View => ViewFacade::make('filament.hooks.branch-switcher'),
        );

        // Register global impersonation banner
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn(): string => ViewFacade::make('components.impersonation-banner')->render(),
        );



        // Register Calendar Widget manually as it's in a custom location
        // \Livewire\Livewire::component('app.filament.dashboard.components.calendar-widget', \App\Filament\Dashboard\Components\CalendarWidget::class);

        // Register Subscription Sync Listeners if Cashier events exist
        if (class_exists(\Laravel\Cashier\Events\SubscriptionCreated::class)) {
            \Illuminate\Support\Facades\Event::listen(
                \Laravel\Cashier\Events\SubscriptionCreated::class,
                [\App\Listeners\SubscriptionSyncListener::class, 'handleSubscriptionCreated']
            );
        }
        if (class_exists(\Laravel\Cashier\Events\SubscriptionUpdated::class)) {
            \Illuminate\Support\Facades\Event::listen(
                \Laravel\Cashier\Events\SubscriptionUpdated::class,
                [\App\Listeners\SubscriptionSyncListener::class, 'handleSubscriptionUpdated']
            );
        }
        if (class_exists(\Laravel\Cashier\Events\SubscriptionDeleted::class)) {
            \Illuminate\Support\Facades\Event::listen(
                \Laravel\Cashier\Events\SubscriptionDeleted::class,
                [\App\Listeners\SubscriptionSyncListener::class, 'handleSubscriptionDeleted']
            );
        }

        // Global Permission Bypass for Business Owner
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if (function_exists('tenant') && tenant() && $user->id === tenant()->owner_user_id) {
                return true;
            }
            return null;
        });
        // Register Ratelimiters
        \Illuminate\Support\Facades\RateLimiter::for('api', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('registration', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });
    }
}
