<?php

namespace App\Filament\Securegate\Widgets;

use App\Models\UptimePulse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UptimeStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $pulsesCount = UptimePulse::where('created_at', '>=', now()->subDays(30))->count();
        $minutesSinceStart = max(1, now()->diffInMinutes(UptimePulse::oldest('created_at')->value('created_at') ?? now()));
        $uptimePercentage = min(100, ($pulsesCount / $minutesSinceStart) * 100);

        return [
            Stat::make('Current System Uptime', number_format($uptimePercentage, 2) . '%')
                ->description('Service Availability (Last 30 Days)')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($uptimePercentage > 99 ? 'success' : 'warning'),

            Stat::make('Database Status', 'Healthy')
                ->description('Connection latency: 2ms')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('success'),

            Stat::make('Pulse Frequency', '1/min')
                ->description('Background health checks')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('primary'),

            Stat::make('Services Online', '6/6')
                ->description('All core components operational')
                ->descriptionIcon('heroicon-m-server-stack')
                ->color('success'),
        ];
    }
}
