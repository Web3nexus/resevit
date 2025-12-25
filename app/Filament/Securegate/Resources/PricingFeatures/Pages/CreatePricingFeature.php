<?php

namespace App\Filament\Securegate\Resources\PricingFeatures\Pages;

use App\Filament\Securegate\Resources\PricingFeatures\PricingFeatureResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePricingFeature extends CreateRecord
{
    protected static string $resource = PricingFeatureResource::class;

    public function getMaxContentWidth(): \Filament\Support\Enums\Width|string|null
    {
        return \Filament\Support\Enums\Width::Full;
    }
}
