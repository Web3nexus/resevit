<?php

namespace App\Filament\Dashboard\Resources\StaffResource\Pages;

use App\Filament\Dashboard\Resources\StaffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $staff = $this->getRecord();

        if ($staff->user) {
            $data['name'] = $staff->user->name;
            $data['email'] = $staff->user->email;

            // Set team ID for Spatie to fetch correct permissions
            setPermissionsTeamId($staff->branch_id);
            $data['roles'] = $staff->user->roles()->pluck('id')->toArray();
            $userPermIds = $staff->user->permissions()->pluck('id')->toArray();

            // Populate feature-specific fields
            $featureMapping = \App\Services\FeaturePermissionManager::getFeaturePermissions();
            foreach ($featureMapping as $feature => $permissions) {
                $featurePermIds = \Spatie\Permission\Models\Permission::whereIn('name', $permissions)
                    ->pluck('id')
                    ->toArray();

                $data['feature_permissions_' . $feature] = array_intersect($userPermIds, $featurePermIds);
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(\App\Services\StaffService::class);

        // Aggregate feature-specific permissions
        $permissions = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'feature_permissions_') && is_array($value)) {
                $permissions = array_merge($permissions, $value);
            }
        }
        $data['user']['permissions'] = array_unique($permissions);

        return $service->updateStaff($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
