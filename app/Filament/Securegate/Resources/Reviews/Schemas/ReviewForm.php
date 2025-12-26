<?php

namespace App\Filament\Securegate\Resources\Reviews\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Review Details')
                    ->schema([
                        \Filament\Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        \Filament\Forms\Components\Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),
                        \Filament\Forms\Components\Toggle::make('is_published')
                            ->default(true)
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('comment')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
