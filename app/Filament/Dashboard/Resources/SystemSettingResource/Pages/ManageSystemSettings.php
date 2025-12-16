<?php

namespace App\Filament\Dashboard\Resources\SystemSettingResource\Pages;

use App\Filament\Dashboard\Resources\SystemSettingResource;
use App\Models\ReservationSetting;
use Filament\Resources\Pages\EditRecord;

class ManageSystemSettings extends EditRecord
{
    protected static string $resource = SystemSettingResource::class;

    protected static ?string $title = 'System Settings';

    /**
     * Override mount to handle singleton pattern without route parameter.
     */
    public function mount(int|string $record = null): void
    {
        // Ignore the $record parameter and use singleton
        $this->record = $this->resolveRecord(1);

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    /**
     * Resolve the record (singleton pattern).
     */
    public function resolveRecord(int|string $key): ReservationSetting
    {
        return ReservationSetting::getInstance();
    }

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for settings page
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return null; // Stay on the same page after save
    }
}
