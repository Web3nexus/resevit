<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AiCreditOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $tenant = tenant();

        if (!$tenant) {
            return [];
        }

        return [
            Stat::make('AI Credit Balance', number_format($tenant->ai_credits ?? 0, 0))
                ->description('Remaining credits for AI features')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color($tenant->ai_credits > 1000 ? 'success' : 'warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'onclick' => "window.location.href='/dashboard/subscription'",
                ]),
        ];
    }
}
