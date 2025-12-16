<?php

namespace App\Filament\Dashboard\Resources\CalendarEventResource\Pages;

use App\Filament\Dashboard\Resources\CalendarEventResource;
use App\Services\CalendarService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalendarEvent extends EditRecord
{
    protected static string $resource = CalendarEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn() => $this->record->isReservationEvent()),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(CalendarService::class);
        return $service->updateEvent($record, $data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
