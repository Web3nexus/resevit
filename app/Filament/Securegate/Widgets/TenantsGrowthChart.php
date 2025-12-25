<?php

namespace App\Filament\Securegate\Widgets;

use App\Models\Tenant;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TenantsGrowthChart extends ChartWidget
{
    protected ?string $heading = 'New Businesses (Last 12 Months)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Check if database is SQLite or MySQL for date formatting
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $data = Tenant::select(
                DB::raw('strftime("%Y-%m", created_at) as month'),
                DB::raw('count(*) as count')
            )
                ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            // MySQL / Postgres (Standard)
            $data = Tenant::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
                ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Businesses',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#0B132B',
                    'backgroundColor' => 'rgba(11, 19, 43, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month')->map(fn($date) => \Carbon\Carbon::createFromFormat('Y-m', $date)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
