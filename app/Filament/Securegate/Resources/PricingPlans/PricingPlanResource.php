<?php

namespace App\Filament\Securegate\Resources\PricingPlans;

use App\Filament\Securegate\Resources\PricingPlans\Pages\CreatePricingPlan;
use App\Filament\Securegate\Resources\PricingPlans\Pages\EditPricingPlan;
use App\Filament\Securegate\Resources\PricingPlans\Pages\ListPricingPlans;
use App\Filament\Securegate\Resources\PricingPlans\Schemas\PricingPlanForm;
use App\Filament\Securegate\Resources\PricingPlans\Tables\PricingPlansTable;
use App\Models\PricingPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Smart Access';

    protected static int|null $navigationSort = 15;

    public static function form(Schema $schema): Schema
    {
        return PricingPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PricingPlansTable::configure($table);
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
            'index' => ListPricingPlans::route('/'),
            'create' => CreatePricingPlan::route('/create'),
            'edit' => EditPricingPlan::route('/{record}/edit'),
        ];
    }
}
