<?php

namespace App\Filament\Securegate\Widgets;

use App\Models\Investor;
use Filament\Widgets\ChartWidget;

class InvestorAnalyticsChart extends ChartWidget
{
    protected ?string $heading = 'Top Investors (by Wallet Balance)';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $investors = Investor::orderByDesc('wallet_balance')->take(5)->get();

        return [
            'datasets' => [
                [
                    'label' => 'Wallet Balance ($)',
                    'data' => $investors->pluck('wallet_balance')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                    ],
                    'borderColor' => [
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $investors->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
