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
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(\App\Services\StaffService::class);

        return $service->updateStaff($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
