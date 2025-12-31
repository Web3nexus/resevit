<?php

namespace App\Filament\Dashboard\Resources\InvestmentOpportunities;


use BackedEnum;
use App\Filament\Dashboard\Resources\InvestmentOpportunities\Pages\CreateInvestmentOpportunity;
use App\Filament\Dashboard\Resources\InvestmentOpportunities\Pages\EditInvestmentOpportunity;
use App\Filament\Dashboard\Resources\InvestmentOpportunities\Pages\ListInvestmentOpportunities;
use App\Filament\Dashboard\Resources\InvestmentOpportunities\Schemas\InvestmentOpportunityForm;
use App\Filament\Dashboard\Resources\InvestmentOpportunities\Tables\InvestmentOpportunitiesTable;
use App\Models\InvestmentOpportunity;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvestmentOpportunityResource extends Resource
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('tenant_id', tenancy()->tenant->id);
    }

    public static function canViewAny(): bool
    {
        return has_feature('investment_opportunities');
    }

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
