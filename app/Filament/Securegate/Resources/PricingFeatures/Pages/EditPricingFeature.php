<?php

namespace App\Filament\Securegate\Resources\PricingFeatures\Pages;

use App\Filament\Securegate\Resources\PricingFeatures\PricingFeatureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPricingFeature extends EditRecord
{
    protected static string $resource = PricingFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width|string|null
    {
        return \Filament\Support\Enums\Width::Full;
    }
}
