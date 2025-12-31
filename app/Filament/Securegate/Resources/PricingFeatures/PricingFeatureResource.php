<?php

namespace App\Filament\Securegate\Resources\PricingFeatures;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\PricingFeatures\Pages\CreatePricingFeature;
use App\Filament\Securegate\Resources\PricingFeatures\Pages\EditPricingFeature;
use App\Filament\Securegate\Resources\PricingFeatures\Pages\ListPricingFeatures;
use App\Filament\Securegate\Resources\PricingFeatures\Schemas\PricingFeatureForm;
use App\Filament\Securegate\Resources\PricingFeatures\Tables\PricingFeaturesTable;
use App\Models\PricingFeature;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PricingFeatureResource extends Resource
{
    protected static ?string $model = PricingFeature::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string | UnitEnum | null $navigationGroup = 'Smart Access';

    protected static int|null $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return PricingFeatureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PricingFeaturesTable::configure($table);
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
            'index' => ListPricingFeatures::route('/'),
            'create' => CreatePricingFeature::route('/create'),
            'edit' => EditPricingFeature::route('/{record}/edit'),
        ];
    }
}
