<?php

namespace App\Filament\Invest\Resources\InvestmentOpportunities\Pages;

use App\Filament\Invest\Resources\InvestmentOpportunities\InvestmentOpportunityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvestmentOpportunities extends ListRecords
{
    protected static string $resource = InvestmentOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
