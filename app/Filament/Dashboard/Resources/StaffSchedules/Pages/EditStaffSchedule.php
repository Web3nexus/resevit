<?php

namespace App\Filament\Dashboard\Resources\StaffSchedules\Pages;

use App\Filament\Dashboard\Resources\StaffSchedules\StaffScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStaffSchedule extends EditRecord
{
    protected static string $resource = StaffScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
