<?php

namespace App\Filament\Securegate\Resources\PricingPlans\Pages;

use App\Filament\Securegate\Resources\PricingPlans\PricingPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePricingPlan extends CreateRecord
{
    protected static string $resource = PricingPlanResource::class;

    public function getMaxContentWidth(): \Filament\Support\Enums\Width|string|null
    {
        return \Filament\Support\Enums\Width::Full;
    }
}
