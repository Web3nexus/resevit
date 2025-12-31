<?php

namespace App\Filament\Securegate\Pages;


use BackedEnum;
use UnitEnum;
use App\Models\UptimePulse;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class SystemMonitor extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static string | UnitEnum | null $navigationGroup = 'System Management';

    protected string $view = 'filament.securegate.pages.system-monitor';

    protected static ?string $title = 'System Monitor';

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Securegate\Widgets\UptimeStatsWidget::class,
            \App\Filament\Securegate\Widgets\ServicesStatusWidget::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            \App\Filament\Securegate\Widgets\UptimePulseChart::class,
        ];
    }

    protected function getViewData(): array
    {
        $lastPulse = UptimePulse::latest('created_at')->first();

        // Calculate uptime for last 30 days
        $totalMinutes = 30 * 24 * 60;
        $pulsesCount = UptimePulse::where('created_at', '>=', now()->subDays(30))->count();
        $uptimePercentage = ($pulsesCount / $totalMinutes) * 100;

        // If we just started, 100% relative to expected pulses so far
        if ($pulsesCount < 1440) { // Less than a day of data
            $minutesSinceStart = max(1, now()->diffInMinutes(UptimePulse::oldest('created_at')->value('created_at') ?? now()));
            $uptimePercentage = min(100, ($pulsesCount / $minutesSinceStart) * 100);
        }

        return [
            'lastPulse' => $lastPulse,
            'uptimePercentage' => number_format($uptimePercentage, 2),
            'systemInfo' => [
                'OS' => PHP_OS,
                'PHP Version' => PHP_VERSION,
                'Laravel Version' => app()->version(),
                'Server IP' => request()->server('SERVER_ADDR', '127.0.0.1'),
            ],
        ];
    }
}
