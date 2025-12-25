<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReservationStatsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return has_feature('analytics');
    }

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->startOfDay();

        $todayReservations = Reservation::whereDate('reservation_time', $today)->count();
        $todayConfirmed = Reservation::whereDate('reservation_time', $today)
            ->where('status', 'confirmed')
            ->count();

        $upcomingReservations = Reservation::where('reservation_time', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $pendingConfirmations = Reservation::where('status', 'pending')
            ->where('reservation_time', '>=', now())
            ->count();

        // Calculate occupancy rate for today
        $totalSeatedToday = Reservation::whereDate('reservation_time', $today)
            ->whereIn('status', ['seated', 'completed'])
            ->sum('party_size');

        // Assuming average capacity of 100 covers per day (this should be configurable)
        $dailyCapacity = 100;
        $occupancyRate = $dailyCapacity > 0 ? round(($totalSeatedToday / $dailyCapacity) * 100, 1) : 0;

        return [
            Stat::make('Today\'s Reservations', $todayReservations)
                ->description("{$todayConfirmed} confirmed")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 12, 15, 18, 22, 25, $todayReservations]),

            Stat::make('Upcoming Reservations', $upcomingReservations)
                ->description('Next 30 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Pending Confirmations', $pendingConfirmations)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingConfirmations > 5 ? 'warning' : 'gray'),

            Stat::make('Today\'s Occupancy', $occupancyRate . '%')
                ->description("{$totalSeatedToday} covers seated")
                ->descriptionIcon('heroicon-m-user-group')
                ->color($occupancyRate > 70 ? 'success' : ($occupancyRate > 40 ? 'warning' : 'danger')),
        ];
    }
}
