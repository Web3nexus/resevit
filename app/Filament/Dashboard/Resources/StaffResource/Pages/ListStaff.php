<?php

namespace App\Filament\Dashboard\Resources\StaffResource\Pages;

use App\Filament\Dashboard\Resources\StaffResource;
use Filament\Resources\Pages\ListRecords;

class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
