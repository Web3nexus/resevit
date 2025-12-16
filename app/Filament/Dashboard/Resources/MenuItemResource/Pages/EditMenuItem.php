<?php

namespace App\Filament\Dashboard\Resources\MenuItemResource\Pages;

use App\Filament\Dashboard\Resources\MenuItemResource;
use Filament\Resources\Pages\EditRecord;

class EditMenuItem extends EditRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
