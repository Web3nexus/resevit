<?php

namespace App\Filament\Dashboard\Resources\StaffResource\Pages;

use App\Filament\Dashboard\Resources\StaffResource;
use App\Services\StaffService;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If creating a new user, handle via StaffService
        if (isset($data['user_id']) && is_array($data['user_id'])) {
            $userData = $data['user_id'];
            $service = app(StaffService::class);
            
            $staff = $service->createStaff([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'phone' => $data['phone'] ?? null,
                'emergency_contact' => $data['emergency_contact'] ?? null,
                'hire_date' => $data['hire_date'],
                'hourly_rate' => $data['hourly_rate'],
                'status' => $data['status'],
                'availability' => $data['availability'] ?? null,
            ], $data['position']);

            // Redirect to list after creation
            $this->redirect(StaffResource::getUrl('index'));
        }

        return $data;
    }
}
