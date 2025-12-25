<?php

namespace App\Filament\Securegate\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserLocationPieChart extends ChartWidget
{
    protected ?string $heading = 'User Locations';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Group users by country. Requires 'country' column on users table.
        $locations = User::select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // If data is scarce, we might simulate for visualisation if requested, 
        // but user asked for "own locations both of them should use pie chart based" 
        // implying real data usage.

        $labels = $locations->pluck('country')->toArray();
        $data = $locations->pluck('total')->toArray();

        // Fallback for empty data to show chart structure if no countries set
        if (empty($data)) {
            $labels = ['Unknown'];
            $data = [User::whereNull('country')->count()];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data,
                    'backgroundColor' => [
                        '#10b981',
                        '#6366f1',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
