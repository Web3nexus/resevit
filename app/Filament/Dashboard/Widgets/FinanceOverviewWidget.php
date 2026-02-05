<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\StaffPayout;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class FinanceOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        $tenant = tenant();
        $totalPaid = StaffPayout::where('status', 'paid')->sum('amount');

        return [
            Stat::make('Wallet Balance', '$' . number_format($user->wallet_balance ?? 0, 2))
                ->description('Current available funds')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success'),
            Stat::make('Total Payouts', '$' . number_format($totalPaid, 2))
                ->description('Total amount paid to staff')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
            Stat::make('AI Credit Balance', number_format($tenant->ai_credits ?? 0, 0))
                ->description('Remaining credits')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color(($tenant->ai_credits ?? 0) > 1000 ? 'success' : 'warning'),
        ];
    }
}
