<?php

namespace App\Filament\Securegate\Resources\AiSettingResource\Pages;

use App\Filament\Securegate\Resources\AiSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAiSetting extends EditRecord
{
    protected static string $resource = AiSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
