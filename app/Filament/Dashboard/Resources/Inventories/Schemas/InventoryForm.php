<?php

namespace App\Filament\Dashboard\Resources\Inventories\Schemas;

use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('current_stock')
                    ->numeric()
                    ->required()
                    ->default(0),
                \Filament\Forms\Components\Select::make('unit')
                    ->options([
                        'pcs' => 'Pieces',
                        'kg' => 'Kilograms',
                        'g' => 'Grams',
                        'l' => 'Liters',
                        'ml' => 'Milliliters',
                        'units' => 'Units',
                    ])
                    ->default('pcs')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('low_stock_threshold')
                    ->numeric()
                    ->helperText('Notify me when stock falls below this level'),
            ]);
    }
}
