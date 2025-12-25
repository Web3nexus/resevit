<?php

namespace App\Filament\Securegate\Widgets;

use App\Models\UptimePulse;
use Filament\Widgets\ChartWidget;

class UptimePulseChart extends ChartWidget
{
    protected ?string $heading = 'CPU Load History';

    protected ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $data = UptimePulse::latest('created_at')
            ->take(60)
            ->get()
            ->reverse();

        return [
            'datasets' => [
                [
                    'label' => 'CPU Usage (%)',
                    'data' => $data->pluck('cpu_usage')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('created_at')->map(fn($date) => $date->format('H:i'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
