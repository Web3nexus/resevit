<?php

namespace App\Filament\Invest\Widgets;

use App\Models\Investment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvestmentPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Investment Activity (6 Months)';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        /** @var \App\Models\Investor $investor */
        $investor = auth()->user();

        // Get last 6 months sums
        $results = Investment::query()
            ->where('investor_id', $investor->id)
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select([
                DB::raw('SUM(amount) as aggregate'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date")
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $labels[] = now()->subMonths($i)->format('M');

            $found = $results->firstWhere('date', $month);
            $data[] = $found ? (float) $found->aggregate : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Invested Amount ($)',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(11, 19, 43, 0.1)',
                    'borderColor' => '#0B132B',
                    'tension' => 0.4,
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
