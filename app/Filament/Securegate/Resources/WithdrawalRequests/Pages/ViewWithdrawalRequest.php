<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests\Pages;

use App\Filament\Securegate\Resources\WithdrawalRequests\WithdrawalRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWithdrawalRequest extends ViewRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
