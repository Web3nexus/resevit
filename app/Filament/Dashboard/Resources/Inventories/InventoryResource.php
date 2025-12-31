<?php

namespace App\Filament\Dashboard\Resources\Inventories;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\Inventories\Pages\CreateInventory;
use App\Filament\Dashboard\Resources\Inventories\Pages\EditInventory;
use App\Filament\Dashboard\Resources\Inventories\Pages\ListInventories;
use App\Filament\Dashboard\Resources\Inventories\Schemas\InventoryForm;
use App\Filament\Dashboard\Resources\Inventories\Tables\InventoriesTable;
use App\Models\Inventory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    protected static string | UnitEnum | null $navigationGroup = 'Menu Management';

    public static function form(Schema $schema): Schema
    {
        return InventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoriesTable::configure($table);
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
            'index' => ListInventories::route('/'),
            'create' => CreateInventory::route('/create'),
            'edit' => EditInventory::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
