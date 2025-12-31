<?php

namespace App\Filament\Securegate\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\IntegrationResource\Pages;
use App\Models\Integration;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class IntegrationResource extends Resource
{
    protected static ?string $model = Integration::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string|UnitEnum|null $navigationGroup = 'Landing Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Integration Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('integrations')
                            ->visibility('public')
                            ->imageEditor()
                            ->getUploadedFileUsing(fn($record) => \App\Helpers\StorageHelper::getUrl($record->logo_path))
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('url')
                            ->url()
                            ->columnSpanFull()
                            ->label('Integration URL'),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->square()
                    ->size(60)
                    ->disk(fn($record) => str_contains($record->logo_path, '::') ? explode('::', $record->logo_path)[0] : config('filesystems.default')),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->limit(40)
                    ->url(fn($record) => $record->url, true),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('order')
                    ->sortable(),
            ])
            ->defaultSort('order')
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
            'index' => Pages\ListIntegrations::route('/'),
            'create' => Pages\CreateIntegration::route('/create'),
            'edit' => Pages\EditIntegration::route('/{record}/edit'),
        ];
    }
}
