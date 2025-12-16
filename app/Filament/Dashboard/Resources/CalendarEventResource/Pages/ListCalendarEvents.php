<?php

namespace App\Filament\Dashboard\Resources\CalendarEventResource\Pages;

use App\Filament\Dashboard\Resources\CalendarEventResource;
use Filament\Resources\Pages\ListRecords;

class ListCalendarEvents extends ListRecords
{
    protected static string $resource = CalendarEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
