<?php

namespace App\Filament\Influencer\Resources\WithdrawalRequests\Pages;

use App\Filament\Influencer\Resources\WithdrawalRequests\WithdrawalRequestResource;
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

    protected function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $influencer = auth('influencer')->user();
        return parent::getEloquentQuery()
            ->where('requester_id', $influencer->id)
            ->where('requester_type', get_class($influencer));
    }
}
