<?php

namespace App\Filament\Invest\Resources\Investments;

use App\Filament\Invest\Resources\Investments\Pages\CreateInvestment;
use App\Filament\Invest\Resources\Investments\Pages\EditInvestment;
use App\Filament\Invest\Resources\Investments\Pages\ListInvestments;
use App\Filament\Invest\Resources\Investments\Schemas\InvestmentForm;
use App\Filament\Invest\Resources\Investments\Tables\InvestmentsTable;
use App\Models\Investment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvestmentResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('investor_id', auth()->id());
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return InvestmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvestmentsTable::configure($table);
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
            'index' => ListInvestments::route('/'),
        ];
    }
}
