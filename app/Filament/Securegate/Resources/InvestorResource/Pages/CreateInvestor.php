<?php

namespace App\Filament\Securegate\Resources\InvestorResource\Pages;

use App\Filament\Securegate\Resources\InvestorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvestor extends CreateRecord
{
    protected static string $resource = InvestorResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
