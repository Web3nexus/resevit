<?php

namespace App\Filament\Dashboard\Resources\AddonResource\Pages;

use App\Filament\Dashboard\Resources\AddonResource;
use Filament\Resources\Pages\EditRecord;

class EditAddon extends EditRecord
{
    protected static string $resource = AddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
