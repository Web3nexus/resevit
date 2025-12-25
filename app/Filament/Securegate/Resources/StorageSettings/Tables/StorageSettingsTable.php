<?php

namespace App\Filament\Securegate\Resources\StorageSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class StorageSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('active_disk')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'public' => 'success',
                        'r2' => 'info',
                        's3' => 'warning',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('cdn_url')
                    ->label('CDN URL')
                    ->placeholder('None'),
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
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
