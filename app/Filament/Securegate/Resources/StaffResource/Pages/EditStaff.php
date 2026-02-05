<?php

namespace App\Filament\Securegate\Resources\StaffResource\Pages;

use App\Filament\Securegate\Resources\StaffResource;
use Filament\Resources\Pages\EditRecord;

class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ViewAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
