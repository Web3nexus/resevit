<?php

namespace App\Filament\Dashboard\Resources\StaffSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaffSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('staff.user.name')
                    ->label('Staff Member')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('day_of_week')
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('shift_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'regular' => 'success',
                        'split' => 'warning',
                        'overtime' => 'danger',
                        'holiday' => 'info',
                    }),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
