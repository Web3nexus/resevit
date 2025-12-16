<?php

namespace App\Filament\Securegate\Resources\InvestorResource\Pages;

use App\Filament\Securegate\Resources\InvestorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInvestor extends ViewRecord
{
    protected static string $resource = InvestorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
