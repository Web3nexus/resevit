<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests\Pages;

use App\Filament\Securegate\Resources\WithdrawalRequests\WithdrawalRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawalRequest extends CreateRecord
{
    protected static string $resource = WithdrawalRequestResource::class;
}
