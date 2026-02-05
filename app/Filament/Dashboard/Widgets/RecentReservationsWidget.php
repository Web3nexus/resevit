<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Reservation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentReservationsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Reservations';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Reservation::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Guest')
                    ->placeholder('Guest')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('reservation_date')
                    ->label('Date')
                    ->date(),
                Tables\Columns\TextColumn::make('reservation_time')
                    ->label('Time'),
                Tables\Columns\TextColumn::make('number_of_guests')
                    ->label('Guests')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'success',
                        'seated' => 'info',
                        'completed' => 'primary',
                        'cancelled' => 'danger',
                        'no_show' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
