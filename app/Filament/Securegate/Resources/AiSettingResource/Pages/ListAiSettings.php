<?php

namespace App\Filament\Securegate\Resources\AiSettingResource\Pages;

use App\Filament\Securegate\Resources\AiSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAiSettings extends ListRecords
{
    protected static string $resource = AiSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
