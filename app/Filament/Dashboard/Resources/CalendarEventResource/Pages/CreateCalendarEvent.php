<?php

namespace App\Filament\Dashboard\Resources\CalendarEventResource\Pages;

use App\Filament\Dashboard\Resources\CalendarEventResource;
use App\Services\CalendarService;
use Filament\Resources\Pages\CreateRecord;

class CreateCalendarEvent extends CreateRecord
{
    protected static string $resource = CalendarEventResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(CalendarService::class);
        return $service->createEvent($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
