<?php

namespace App\Filament\Dashboard\Resources\StaffPayoutResource\Pages;

use App\Filament\Dashboard\Resources\StaffPayoutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaffPayout extends EditRecord
{
    protected static string $resource = StaffPayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
