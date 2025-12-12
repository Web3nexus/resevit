<?php

namespace App\Filament\Dashboard\Resources\StaffPayoutResource\Pages;

use App\Filament\Dashboard\Resources\StaffPayoutResource;
use Filament\Resources\Pages\ListRecords;

class ListStaffPayouts extends ListRecords
{
    protected static string $resource = StaffPayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
