<?php

namespace App\Filament\Securegate\Resources\Influencers;

use App\Filament\Securegate\Resources\Influencers\Pages\CreateInfluencer;
use App\Filament\Securegate\Resources\Influencers\Pages\EditInfluencer;
use App\Filament\Securegate\Resources\Influencers\Pages\ListInfluencers;
use App\Filament\Securegate\Resources\Influencers\Schemas\InfluencerForm;
use App\Filament\Securegate\Resources\Influencers\Tables\InfluencersTable;
use App\Models\Influencer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InfluencerResource extends Resource
{
    protected static ?string $model = Influencer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing Tools';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return InfluencerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InfluencersTable::configure($table);
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
            'index' => ListInfluencers::route('/'),
            'create' => CreateInfluencer::route('/create'),
            'edit' => EditInfluencer::route('/{record}/edit'),
        ];
    }
}
