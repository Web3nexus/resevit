<?php

namespace App\Filament\Dashboard\Resources\PayoutRequestResource\Pages;

use App\Filament\Dashboard\Resources\PayoutRequestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePayoutRequest extends CreateRecord
{
    protected static string $resource = PayoutRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        return $data;
    }
}
