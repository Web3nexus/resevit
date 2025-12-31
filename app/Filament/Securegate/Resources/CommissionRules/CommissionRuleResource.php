<?php

namespace App\Filament\Securegate\Resources\CommissionRules;

use App\Filament\Securegate\Resources\CommissionRules\Pages\CreateCommissionRule;
use App\Filament\Securegate\Resources\CommissionRules\Pages\EditCommissionRule;
use App\Filament\Securegate\Resources\CommissionRules\Pages\ListCommissionRules;
use App\Filament\Securegate\Resources\CommissionRules\Schemas\CommissionRuleForm;
use App\Filament\Securegate\Resources\CommissionRules\Tables\CommissionRulesTable;
use App\Models\CommissionRule;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CommissionRuleResource extends Resource
{
    protected static ?string $model = CommissionRule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing Tools';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CommissionRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommissionRulesTable::configure($table);
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
            'index' => ListCommissionRules::route('/'),
            'create' => CreateCommissionRule::route('/create'),
            'edit' => EditCommissionRule::route('/{record}/edit'),
        ];
    }
}
