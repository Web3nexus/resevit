<?php

namespace App\Filament\Securegate\Resources\InvestmentOpportunities\Pages;

use App\Filament\Securegate\Resources\InvestmentOpportunities\InvestmentOpportunityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentOpportunity extends EditRecord
{
    protected static string $resource = InvestmentOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
