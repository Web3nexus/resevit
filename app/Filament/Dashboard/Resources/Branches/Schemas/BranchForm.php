<?php

namespace App\Filament\Dashboard\Resources\Branches\Schemas;

use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, \Filament\Schemas\Components\Utilities\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                \Filament\Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\Textarea::make('address')
                    ->columnSpanFull(),
                \Filament\Forms\Components\TextInput::make('phone')
                    ->tel(),
                \Filament\Forms\Components\TextInput::make('email')
                    ->email(),
                \Filament\Forms\Components\KeyValue::make('opening_hours')
                    ->keyLabel('Day')
                    ->valueLabel('Hours')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
