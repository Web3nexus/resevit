<?php

namespace App\Filament\Securegate\Resources\LandingPages;

use App\Filament\Securegate\Resources\LandingPages\Pages\CreateLandingPage;
use App\Filament\Securegate\Resources\LandingPages\Pages\EditLandingPage;
use App\Filament\Securegate\Resources\LandingPages\Pages\ListLandingPages;
use App\Filament\Securegate\Resources\LandingPages\Schemas\LandingPageForm;
use App\Filament\Securegate\Resources\LandingPages\Tables\LandingPagesTable;
use App\Models\LandingPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LandingPageResource extends Resource
{
    protected static ?string $model = LandingPage::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Landing Management';

    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-window';

    public static function form(Schema $schema): Schema
    {
        return LandingPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LandingPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLandingPages::route('/'),
            'create' => CreateLandingPage::route('/create'),
            'edit' => EditLandingPage::route('/{record}/edit'),
        ];
    }
}
