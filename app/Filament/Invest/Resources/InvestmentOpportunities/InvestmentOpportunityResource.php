<?php

namespace App\Filament\Invest\Resources\InvestmentOpportunities;

use App\Filament\Invest\Resources\InvestmentOpportunities\Pages\CreateInvestmentOpportunity;
use App\Filament\Invest\Resources\InvestmentOpportunities\Pages\EditInvestmentOpportunity;
use App\Filament\Invest\Resources\InvestmentOpportunities\Pages\ListInvestmentOpportunities;
use App\Filament\Invest\Resources\InvestmentOpportunities\Schemas\InvestmentOpportunityForm;
use App\Filament\Invest\Resources\InvestmentOpportunities\Tables\InvestmentOpportunitiesTable;
use App\Models\InvestmentOpportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvestmentOpportunityResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('validation_status', 'approved');
    }

    public static function canCreate(): bool
    {
        return false;
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
        ];
    }
}
