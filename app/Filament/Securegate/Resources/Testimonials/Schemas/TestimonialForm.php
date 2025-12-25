<?php

namespace App\Filament\Securegate\Resources\Testimonials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->collection('avatars')
                    ->disk('public')
                    ->avatar()
                    ->circle()
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('role'),
                TextInput::make('company'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ])
                    ->required()
                    ->default(5),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
