<?php

namespace App\Filament\Securegate\Resources\StorageSettings\Pages;

use App\Filament\Securegate\Resources\StorageSettings\StorageSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStorageSettings extends ListRecords
{
    protected static string $resource = StorageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
