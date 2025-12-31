<?php

namespace App\Filament\Dashboard\Resources\Promotions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Leave empty for all branches (Business-wide)'),
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'discount' => 'Automatic Discount',
                        'coupon' => 'Coupon Code',
                        'popup' => 'Website Popup',
                        'banner' => 'Website Banner',
                    ])
                    ->required()
                    ->live(),
                \Filament\Forms\Components\TextInput::make('code')
                    ->visible(fn(Get $get) => $get('type') === 'coupon')
                    ->required(fn(Get $get) => $get('type') === 'coupon')
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->prefix(fn(Get $get) => $get('type') === 'discount' ? '%' : '$')
                    ->label('Discount Value'),
                \Filament\Forms\Components\DateTimePicker::make('start_date'),
                \Filament\Forms\Components\DateTimePicker::make('end_date'),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
