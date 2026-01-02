<?php

namespace App\Filament\Securegate\Resources\MarketingMaterials\Pages;

use App\Filament\Securegate\Resources\MarketingMaterials\MarketingMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMarketingMaterial extends EditRecord
{
    protected static string $resource = MarketingMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
