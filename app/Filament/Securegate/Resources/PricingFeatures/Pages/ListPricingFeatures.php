<?php

namespace App\Filament\Securegate\Resources\PricingFeatures\Pages;

use App\Filament\Securegate\Resources\PricingFeatures\PricingFeatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPricingFeatures extends ListRecords
{
    protected static string $resource = PricingFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
