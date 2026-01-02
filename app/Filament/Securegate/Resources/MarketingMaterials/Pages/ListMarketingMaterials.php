<?php

namespace App\Filament\Securegate\Resources\MarketingMaterials\Pages;

use App\Filament\Securegate\Resources\MarketingMaterials\MarketingMaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMarketingMaterials extends ListRecords
{
    protected static string $resource = MarketingMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
