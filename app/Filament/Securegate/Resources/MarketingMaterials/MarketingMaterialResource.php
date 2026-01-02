<?php

namespace App\Filament\Securegate\Resources\MarketingMaterials;

use App\Filament\Securegate\Resources\MarketingMaterials\Pages\CreateMarketingMaterial;
use App\Filament\Securegate\Resources\MarketingMaterials\Pages\EditMarketingMaterial;
use App\Filament\Securegate\Resources\MarketingMaterials\Pages\ListMarketingMaterials;
use App\Filament\Securegate\Resources\MarketingMaterials\Schemas\MarketingMaterialForm;
use App\Filament\Securegate\Resources\MarketingMaterials\Tables\MarketingMaterialsTable;
use App\Models\MarketingMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarketingMaterialResource extends Resource
{
    protected static ?string $model = MarketingMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MarketingMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarketingMaterialsTable::configure($table);
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
            'index' => ListMarketingMaterials::route('/'),
            'create' => CreateMarketingMaterial::route('/create'),
            'edit' => EditMarketingMaterial::route('/{record}/edit'),
        ];
    }
}
