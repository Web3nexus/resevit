<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests\Pages;

use App\Filament\Securegate\Resources\WithdrawalRequests\WithdrawalRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawalRequests extends ListRecords
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
