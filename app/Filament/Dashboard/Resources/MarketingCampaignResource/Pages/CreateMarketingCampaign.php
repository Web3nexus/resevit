<?php

namespace App\Filament\Dashboard\Resources\MarketingCampaignResource\Pages;

use App\Filament\Dashboard\Resources\MarketingCampaignResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMarketingCampaign extends CreateRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }
}
