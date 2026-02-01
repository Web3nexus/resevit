<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\WebsiteTemplateResource\Pages;
use App\Models\WebsiteTemplate;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteTemplateResource extends Resource
{
    protected static ?string $model = WebsiteTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Builder';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, \Filament\Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\FileUpload::make('thumbnail_path')
                    ->image()
                    ->directory('website-templates')
                    ->columnSpanFull(),
                Section::make('Schema & Content')
                    ->schema([
                        Forms\Components\Textarea::make('structure_schema')
                            ->helperText('JSON definition of editable fields')
                            ->rows(10)
                            ->json(),
                        Forms\Components\Textarea::make('default_content')
                            ->helperText('Default values for the schema')
                            ->rows(10)
                            ->json(),
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Toggle::make('is_premium')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_path'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_premium')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn ($record) => view('filament.dashboard.pages.website-builder-preview', [
                        'url' => route('templates.preview', ['slug' => $record->slug]),
                    ]))
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListWebsiteTemplates::route('/'),
            'create' => Pages\CreateWebsiteTemplate::route('/create'),
            'edit' => Pages\EditWebsiteTemplate::route('/{record}/edit'),
        ];
    }
}
