<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class EarningsSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get paid orders for this month
        $paidOrders = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth]);

        $grossEarnings = $paidOrders->sum('total_amount');
        $serviceFees = $paidOrders->sum('service_fee');
        $platformFee = $grossEarnings * 0.03; // 3% platform fee (configurable)
        $netEarnings = $grossEarnings - $platformFee;

        // Calculate trend (compare to last month)
        $lastMonthGross = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->sum('total_amount');

        $trend = $lastMonthGross > 0
            ? (($grossEarnings - $lastMonthGross) / $lastMonthGross) * 100
            : 0;

        return [
            Stat::make('Gross Earnings', '$' . number_format($grossEarnings, 2))
                ->description(($trend >= 0 ? '+' : '') . number_format($trend, 1) . '% from last month')
                ->descriptionIcon($trend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend >= 0 ? 'success' : 'danger')
                ->chart($this->getChartData()),

            Stat::make('Platform Fees', '$' . number_format($platformFee, 2))
                ->description('3% of gross earnings')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),

            Stat::make('Net Earnings', '$' . number_format($netEarnings, 2))
                ->description('After platform fees')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }

    protected function getChartData(): array
    {
        // Last 7 days revenue
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::query()
                ->where('payment_status', 'paid')
                ->whereDate('paid_at', $date)
                ->sum('total_amount');
            $data[] = $revenue;
        }

        return $data;
    }
}
