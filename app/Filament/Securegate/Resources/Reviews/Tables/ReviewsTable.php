<?php

namespace App\Filament\Securegate\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Reviewer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tenant.name')
                    ->label('Business')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->numeric()
                    ->sortable(),
                \Filament\Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
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
