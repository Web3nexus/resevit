<?php

namespace App\Filament\Dashboard\Resources\Promotions;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\Promotions\Pages\CreatePromotion;
use App\Filament\Dashboard\Resources\Promotions\Pages\EditPromotion;
use App\Filament\Dashboard\Resources\Promotions\Pages\ListPromotions;
use App\Filament\Dashboard\Resources\Promotions\Schemas\PromotionForm;
use App\Filament\Dashboard\Resources\Promotions\Tables\PromotionsTable;
use App\Models\Promotion;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    protected static string | UnitEnum | null $navigationGroup = 'Marketing Management';

    public static function form(Schema $schema): Schema
    {
        return PromotionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionsTable::configure($table);
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
            'index' => ListPromotions::route('/'),
            'create' => CreatePromotion::route('/create'),
            'edit' => EditPromotion::route('/{record}/edit'),
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
