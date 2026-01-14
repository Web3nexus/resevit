<?php

namespace App\Filament\Securegate\Resources\MarketingMaterials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MarketingMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
                        'image' => 'Image',
                        'link' => 'Link',
                        'text' => 'Text',
                    ])
                    ->required()
                    ->live(),
                FileUpload::make('file_path')
                    ->label('File')
                    ->image()
                    ->maxSize(5120) // 5MB limit
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                    ->visible(fn($get) => $get('type') === 'image')
                    ->directory('marketing-materials'),
                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->visible(fn($get) => $get('type') === 'link')
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
