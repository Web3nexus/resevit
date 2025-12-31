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

        // Aggregate feature-specific permissions
        $permissions = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'feature_permissions_') && is_array($value)) {
                $permissions = array_merge($permissions, $value);
            }
        }
        $data['user']['permissions'] = array_unique($permissions);

        // Position acts as the role
        return $service->createStaff($data, $data['position']);
    }
}
