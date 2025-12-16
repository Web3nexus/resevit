<?php

namespace App\Filament\Dashboard\Resources\StaffResource\Pages;

use App\Filament\Dashboard\Resources\StaffResource;
use App\Services\StaffService;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(StaffService::class);

        // Position acts as the role
        return $service->createStaff($data, $data['position']);
    }
}
