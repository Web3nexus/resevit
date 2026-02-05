<?php

namespace App\Filament\Securegate\Resources\StaffResource\Pages;

use App\Filament\Securegate\Resources\StaffResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStaff extends ViewRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }
}
