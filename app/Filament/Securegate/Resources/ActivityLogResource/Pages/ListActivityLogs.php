<?php

namespace App\Filament\Securegate\Resources\ActivityLogResource\Pages;

use App\Filament\Securegate\Resources\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
