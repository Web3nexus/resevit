<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests\Pages;

use App\Filament\Securegate\Resources\WithdrawalRequests\WithdrawalRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalRequest extends EditRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
