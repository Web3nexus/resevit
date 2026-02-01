<?php

namespace App\Filament\Dashboard\Resources\PayoutRequestResource\Pages;

use App\Filament\Dashboard\Resources\PayoutRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListPayoutRequests extends ListRecords
{
    protected static string $resource = PayoutRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Request Payout')
                ->visible(fn () => ! Auth::user()->hasRole('admin')),
        ];
    }
}
