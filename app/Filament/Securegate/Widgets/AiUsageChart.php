<?php

namespace App\Filament\Securegate\Widgets;

use Filament\Widgets\ChartWidget;

class AiUsageChart extends ChartWidget
{
    protected ?string $heading = 'AI Usage Analytics';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Simulated data for demonstration as no explicit usage logs exist yet
        return [
            'datasets' => [
                [
                    'label' => 'Content Generation',
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
                [
                    'label' => 'Image Generation',
                    'data' => [28, 48, 40, 19, 86, 27, 90],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
            ],
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
