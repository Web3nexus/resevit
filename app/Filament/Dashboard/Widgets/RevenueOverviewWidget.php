<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueOverviewWidget extends ChartWidget
{
    protected ?string $heading = 'Revenue Overview';

    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        // Determine date range
        [$start, $end, $groupBy] = match ($filter) {
            'today' => [Carbon::today(), Carbon::tomorrow(), 'hour'],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), 'day'],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), 'day'],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear(), 'month'],
            default => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), 'day'],
        };

        // Query paid orders
        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [$start, $end])
            ->get();

        // Group by time period
        $grouped = $orders->groupBy(function ($order) use ($groupBy) {
            return match ($groupBy) {
                'hour' => $order->paid_at->format('H:00'),
                'day' => $order->paid_at->format('M d'),
                'month' => $order->paid_at->format('M Y'),
            };
        });

        $labels = $grouped->keys()->toArray();
        $data = $grouped->map(fn($group) => $group->sum('total'))->values()->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
