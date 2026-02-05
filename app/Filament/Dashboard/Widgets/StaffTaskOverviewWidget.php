<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Branch;
use App\Models\Staff;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StaffTaskOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Staff', Staff::count())
                ->description('Registered staff members')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Active Tasks', Task::whereIn('status', ['pending', 'in_progress'])->count())
                ->description('Tasks requiring attention')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),
            Stat::make('Branches', Branch::count())
                ->description('Total active locations')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),
        ];
    }
}
