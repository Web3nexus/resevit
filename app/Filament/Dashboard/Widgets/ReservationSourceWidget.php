<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Reservation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReservationSourceWidget extends ChartWidget
{
    protected ?string $heading = 'Reservation Sources';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Reservation::query()
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get();

        $labels = $data->pluck('source')->map(fn($source) => ucfirst($source))->toArray();
        $values = $data->pluck('total')->toArray();

        // If no data, provide dummy for visual feedback
        if (empty($labels)) {
            $labels = ['No Data'];
            $values = [1];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Reservations',
                    'data' => $values,
                    'backgroundColor' => [
                        '#3b82f6', // blue (Website)
                        '#10b981', // green (WhatsApp)
                        '#f59e0b', // amber (FB)
                        '#ec4899', // pink (IG)
                        '#8b5cf6', // purple (Walk-in)
                    ],
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
