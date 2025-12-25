<?php

namespace App\Filament\Invest\Resources\Investments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class InvestmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('opportunity.title')
                    ->label('Opportunity')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('current_value')
                    ->money('USD')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Invested At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($query) => $query->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($query) => $query->whereDate('created_at', '<=', $data['until']));
                    })
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
