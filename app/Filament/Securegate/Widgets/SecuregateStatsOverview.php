<?php

namespace App\Filament\Securegate\Widgets;

use App\Models\Customer;
use App\Models\Investor;
use App\Models\NewsletterSubscriber;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SecuregateStatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user() instanceof \App\Models\Admin;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Businesses', Tenant::whereNotNull('owner_user_id')->count())
                ->description('Active Tenants with Owners')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary')
                ->chart([
                    Tenant::whereNotNull('owner_user_id')->where('created_at', '>=', now()->subDays(6))->count(),
                    Tenant::whereNotNull('owner_user_id')->where('created_at', '>=', now()->subDays(5))->count(),
                    Tenant::whereNotNull('owner_user_id')->where('created_at', '>=', now()->subDays(4))->count(),
                    Tenant::whereNotNull('owner_user_id')->where('created_at', '>=', now()->subDays(3))->count(),
                    Tenant::whereNotNull('owner_user_id')->where('created_at', '>=', now()->subDays(2))->count(),
                    Tenant::whereNotNull('owner_user_id')->where('created_at', '>=', now()->subDays(1))->count(),
                    Tenant::whereNotNull('owner_user_id')->count(),
                ]),

            Stat::make('Total Customers', Customer::count())
                ->description('Registered Users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Investors', Investor::count())
                ->description('Active Investors')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Newsletter Subs', NewsletterSubscriber::count())
                ->description('Marketing Audience')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),
        ];
    }
}
