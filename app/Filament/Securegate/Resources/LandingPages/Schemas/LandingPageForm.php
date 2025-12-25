<?php

namespace App\Filament\Securegate\Resources\LandingPages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\LandingPage;
use Illuminate\Support\Str;

class LandingPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique(LandingPage::class, 'slug', ignoreRecord: true),
                Section::make('SEO Metadata')
                    ->schema([
                        TextInput::make('meta_title'),
                        Textarea::make('meta_description')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
