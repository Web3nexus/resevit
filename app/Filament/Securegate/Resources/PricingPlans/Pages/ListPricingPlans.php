<?php

namespace App\Filament\Securegate\Resources\PricingPlans\Pages;

use App\Filament\Securegate\Resources\PricingPlans\PricingPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPricingPlans extends ListRecords
{
    protected static string $resource = PricingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
