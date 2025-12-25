<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Set;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cake';

    protected static string|\UnitEnum|null $navigationGroup = 'Menu Management';

    protected static int|null $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return has_feature('menu');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Item Details')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('base_price')
                            ->label('Base Price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Available')
                            ->default(true),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        FileUpload::make('image_path')
                            ->label('Image')
                            ->image()
                            ->directory('menu-items')
                            ->visibility('public')
                            ->columnSpanFull(),

                    ])->columns(2),

                Section::make('Variants')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Variant Name (e.g. Small)'),
                                Forms\Components\TextInput::make('price_adjustment')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Extra Price')
                                    ->prefix('+$'),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->defaultItems(0),
                    ]),

                Section::make('Addons')
                    ->schema([
                        Forms\Components\CheckboxList::make('addons')
                            ->relationship('addons', 'name')
                            ->columns(2)
                            ->helperText('Select available addons for this item'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
