<?php

namespace App\Filament\Securegate\Resources\PricingPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricingPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('price_monthly')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_yearly')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cta_text')
                    ->searchable(),
                TextColumn::make('cta_url')
                    ->searchable(),
                IconColumn::make('is_featured')
                    ->boolean(),
                IconColumn::make('is_free')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('trial_days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stripe_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
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
