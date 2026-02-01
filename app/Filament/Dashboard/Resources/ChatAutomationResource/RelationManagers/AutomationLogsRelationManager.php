<?php

namespace App\Filament\Dashboard\Resources\ChatAutomationResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AutomationLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chat.customer_name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('step_index')
                    ->label('Last Step'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'started' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
