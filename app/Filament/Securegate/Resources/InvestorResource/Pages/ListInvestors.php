<?php

namespace App\Filament\Securegate\Resources\InvestorResource\Pages;

use App\Filament\Securegate\Resources\InvestorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestors extends ListRecords
{
    protected static string $resource = InvestorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
