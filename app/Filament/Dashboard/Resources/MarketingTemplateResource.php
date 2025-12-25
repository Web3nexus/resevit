<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\MarketingTemplateResource\Pages;
use App\Models\MarketingTemplate;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarketingTemplateResource extends Resource
{
    protected static ?string $model = MarketingTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static string | \UnitEnum | null $navigationGroup = 'Marketing';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'social' => 'Social Media',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('subject')
                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('content')
                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'email')
                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'email')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'social' => 'Social Media',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingTemplates::route('/'),
            'create' => Pages\CreateMarketingTemplate::route('/create'),
            'edit' => Pages\EditMarketingTemplate::route('/{record}/edit'),
        ];
    }
}
