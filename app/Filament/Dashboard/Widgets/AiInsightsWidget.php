<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AiInsightsWidget extends BaseWidget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('AI Business Insights', 'Revenue is up 12%')
                ->description('Saturday 7 PM is your busiest time. Ensure more staff are on duty.')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success'),
        ];
    }
}
