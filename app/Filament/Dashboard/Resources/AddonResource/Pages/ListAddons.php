<?php

namespace App\Filament\Dashboard\Resources\AddonResource\Pages;

use App\Filament\Dashboard\Resources\AddonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddons extends ListRecords
{
    protected static string $resource = AddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
