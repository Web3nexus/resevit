<?php

namespace App\Filament\Securegate\Resources\PricingFeatures\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PricingFeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('feature_key', \Illuminate\Support\Str::snake($state))),
                TextInput::make('feature_key')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('description'),
                TextInput::make('category')
                    ->placeholder('e.g. Operations, Marketing, AI'),
                Toggle::make('is_billable')
                    ->label('Is Billable Item')
                    ->default(true),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
