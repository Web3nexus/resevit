<?php

namespace App\Filament\Securegate\Resources\InvestorResource\Pages;

use App\Filament\Securegate\Resources\InvestorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestor extends EditRecord
{
    protected static string $resource = InvestorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
