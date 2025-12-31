<?php

namespace App\Filament\Securegate\Resources\InvestmentOpportunities;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\InvestmentOpportunities\Pages\CreateInvestmentOpportunity;
use App\Filament\Securegate\Resources\InvestmentOpportunities\Pages\EditInvestmentOpportunity;
use App\Filament\Securegate\Resources\InvestmentOpportunities\Pages\ListInvestmentOpportunities;
use App\Filament\Securegate\Resources\InvestmentOpportunities\Schemas\InvestmentOpportunityForm;
use App\Filament\Securegate\Resources\InvestmentOpportunities\Tables\InvestmentOpportunitiesTable;
use App\Models\InvestmentOpportunity;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvestmentOpportunityResource extends Resource
{
    protected static ?string $model = InvestmentOpportunity::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string | UnitEnum | null $navigationGroup = 'Landing Management';

    protected static int|null $navigationSort = 17;

    public static function form(Schema $schema): Schema
    {
        return InvestmentOpportunityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvestmentOpportunitiesTable::configure($table);
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
            'index' => ListInvestmentOpportunities::route('/'),
            'create' => CreateInvestmentOpportunity::route('/create'),
            'edit' => EditInvestmentOpportunity::route('/{record}/edit'),
        ];
    }
}
