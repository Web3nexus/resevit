<?php

namespace App\Filament\Securegate\Resources\StorageSettings\Pages;

use App\Filament\Securegate\Resources\StorageSettings\StorageSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStorageSetting extends EditRecord
{
    protected static string $resource = StorageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
