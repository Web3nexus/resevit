<?php

namespace App\Filament\Securegate\Resources\InvestmentOpportunities\Pages;

use App\Filament\Securegate\Resources\InvestmentOpportunities\InvestmentOpportunityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvestmentOpportunities extends ListRecords
{
    protected static string $resource = InvestmentOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
