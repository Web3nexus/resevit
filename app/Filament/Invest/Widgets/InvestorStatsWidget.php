<?php

namespace App\Filament\Invest\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvestorStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        /** @var \App\Models\Investor $investor */
        $investor = auth()->user();
        $investor->refresh();

        $totalInvested = $investor->investments()->sum('amount');
        $totalPayouts = $investor->payouts()->sum('amount');
        $walletBalance = $investor->wallet_balance;

        return [
            Stat::make('Wallet Balance', '$' . number_format($walletBalance, 2))
                ->description('Available for investment')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success'),
            Stat::make('Total Invested', '$' . number_format($totalInvested, 2))
                ->description('Across all opportunities')
                ->descriptionIcon('heroicon-m-banknotes'),
            Stat::make('Total Returns', '$' . number_format($totalPayouts, 2))
                ->description('Total ROI paid out')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
