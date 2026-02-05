<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class SalesMetricsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Total sales this month
        $totalSales = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Average order value
        $totalRevenue = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        // Compare to last month
        $lastMonthSales = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->count();

        $salesTrend = $lastMonthSales > 0
            ? (($totalSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // Online orders percentage
        $onlineOrders = Order::query()
            ->where('payment_status', 'paid')
            ->where('order_source', 'online')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->count();

        $onlinePercentage = $totalSales > 0 ? ($onlineOrders / $totalSales) * 100 : 0;

        return [
            Stat::make('Total Sales', number_format($totalSales))
                ->description(($salesTrend >= 0 ? '+' : '') . number_format($salesTrend, 1) . '% from last month')
                ->descriptionIcon($salesTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesTrend >= 0 ? 'success' : 'danger')
                ->chart($this->getSalesChartData()),

            Stat::make('Average Order Value', '$' . number_format($averageOrderValue, 2))
                ->description('Per order this month')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Online Orders', number_format($onlinePercentage, 1) . '%')
                ->description($onlineOrders . ' of ' . $totalSales . ' orders')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('success'),
        ];
    }

    protected function getSalesChartData(): array
    {
        // Last 7 days sales count
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::query()
                ->where('payment_status', 'paid')
                ->whereDate('paid_at', $date)
                ->count();
            $data[] = $sales;
        }

        return $data;
    }
}
