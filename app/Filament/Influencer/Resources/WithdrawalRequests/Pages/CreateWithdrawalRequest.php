<?php

namespace App\Filament\Influencer\Resources\WithdrawalRequests\Pages;

use App\Filament\Influencer\Resources\WithdrawalRequests\WithdrawalRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawalRequest extends CreateRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $influencer = auth('influencer')->user();

        if (!$influencer->bank_name) {
            throw new \Exception('Please update your bank details in your profile first.');
        }

        $data['requester_id'] = $influencer->id;
        $data['requester_type'] = get_class($influencer);
        $data['status'] = 'pending';
        $data['bank_details'] = [
            'bank_name' => $influencer->bank_name,
            'account_name' => $influencer->account_name,
            'account_number' => $influencer->account_number,
            'iban' => $influencer->iban,
            'swift_code' => $influencer->swift_code,
        ];

        return $data;
    }
}
