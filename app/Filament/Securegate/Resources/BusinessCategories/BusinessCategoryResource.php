<?php

namespace App\Filament\Securegate\Resources\BusinessCategories;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\BusinessCategories\Pages\CreateBusinessCategory;
use App\Filament\Securegate\Resources\BusinessCategories\Pages\EditBusinessCategory;
use App\Filament\Securegate\Resources\BusinessCategories\Pages\ListBusinessCategories;
use App\Filament\Securegate\Resources\BusinessCategories\Schemas\BusinessCategoryForm;
use App\Filament\Securegate\Resources\BusinessCategories\Tables\BusinessCategoriesTable;
use App\Models\BusinessCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusinessCategoryResource extends Resource
{
    protected static ?string $model = BusinessCategory::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | UnitEnum | null $navigationGroup = 'Internal Users';

    public static function form(Schema $schema): Schema
    {
        return BusinessCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessCategoriesTable::configure($table);
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
            'index' => ListBusinessCategories::route('/'),
            'create' => CreateBusinessCategory::route('/create'),
            'edit' => EditBusinessCategory::route('/{record}/edit'),
        ];
    }
}
