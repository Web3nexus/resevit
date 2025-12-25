<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DeviceUsagePieChart extends ChartWidget
{
    protected ?string $heading = 'Device Usage';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Simple heuristic for Mobile vs Desktop based on user_agent in sessions table
        $mobileCount = DB::table('sessions')->where('user_agent', 'like', '%Mobile%')
            ->orWhere('user_agent', 'like', '%Android%')
            ->orWhere('user_agent', 'like', '%iPhone%')
            ->count();

        $desktopCount = DB::table('sessions')->where(function ($query) {
            $query->where('user_agent', 'not like', '%Mobile%')
                ->where('user_agent', 'not like', '%Android%')
                ->where('user_agent', 'not like', '%iPhone%')
                ->orWhereNull('user_agent');
        })->count();

        return [
            'datasets' => [
                [
                    'label' => 'Device Type',
                    'data' => [$desktopCount, $mobileCount],
                    'backgroundColor' => [
                        '#3b82f6', // Desktop Blue
                        '#eab308', // Mobile Yellow
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Desktop / Unknown', 'Mobile'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
