<?php

namespace App\Filament\Securegate\Resources\StorageSettings;

use App\Filament\Securegate\Resources\StorageSettings\Pages\CreateStorageSetting;
use App\Filament\Securegate\Resources\StorageSettings\Pages\EditStorageSetting;
use App\Filament\Securegate\Resources\StorageSettings\Pages\ListStorageSettings;
use App\Filament\Securegate\Resources\StorageSettings\Schemas\StorageSettingForm;
use App\Filament\Securegate\Resources\StorageSettings\Tables\StorageSettingsTable;
use App\Models\StorageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StorageSettingResource extends Resource
{
    protected static ?string $model = StorageSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cloud';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Settings';

    protected static ?string $navigationLabel = 'CDN & Storage';

    public static function form(Schema $schema): Schema
    {
        return StorageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StorageSettingsTable::configure($table);
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
            'index' => ListStorageSettings::route('/'),
            'create' => CreateStorageSetting::route('/create'),
            'edit' => EditStorageSetting::route('/{record}/edit'),
        ];
    }
}
