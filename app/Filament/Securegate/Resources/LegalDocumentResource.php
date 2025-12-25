<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\LegalDocumentResource\Pages;
use App\Models\LegalDocument;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class LegalDocumentResource extends Resource
{
    protected static ?string $model = LegalDocument::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    protected static \UnitEnum|string|null $navigationGroup = 'Landing Management';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Content')
                    ->schema([
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->extraInputAttributes(['style' => 'min-height: 400px']),
                    ]),

                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('is_published')
                    ->label('Published'),

                TextColumn::make('order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLegalDocuments::route('/'),
            'create' => Pages\CreateLegalDocument::route('/create'),
            'edit' => Pages\EditLegalDocument::route('/{record}/edit'),
        ];
    }
}
