<?php

namespace App\Filament\Securegate\Resources\MarketingCampaignResource\Pages;

use App\Filament\Securegate\Resources\MarketingCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingCampaigns extends ListRecords
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
