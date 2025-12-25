<?php

namespace App\Filament\Dashboard\Resources\StaffSchedules\Pages;

use App\Filament\Dashboard\Resources\StaffSchedules\StaffScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaffSchedules extends ListRecords
{
    protected static string $resource = StaffScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
