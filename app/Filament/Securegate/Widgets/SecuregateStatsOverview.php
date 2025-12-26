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
            Stat::make('Active Businesses', Tenant::where('status', 'active')->count())
                ->description('Businesses with active status')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success')
                ->chart([
                    Tenant::where('status', 'active')->where('created_at', '>=', now()->subDays(6))->count(),
                    Tenant::where('status', 'active')->where('created_at', '>=', now()->subDays(5))->count(),
                    Tenant::where('status', 'active')->where('created_at', '>=', now()->subDays(4))->count(),
                    Tenant::where('status', 'active')->where('created_at', '>=', now()->subDays(3))->count(),
                    Tenant::where('status', 'active')->where('created_at', '>=', now()->subDays(2))->count(),
                    Tenant::where('status', 'active')->where('created_at', '>=', now()->subDays(1))->count(),
                    Tenant::where('status', 'active')->count(),
                ]),

            Stat::make('Total Businesses', Tenant::count())
                ->description('All businesses in the system')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('gray'),

            Stat::make('Total Investors', Investor::count())
                ->description('Active Investors')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Total Customers', Customer::count())
                ->description('Registered Users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
}
