<?php

namespace App\Filament\Dashboard\Resources\ChatAutomationResource\Pages;

use App\Filament\Dashboard\Resources\ChatAutomationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChatAutomation extends EditRecord
{
    protected static string $resource = ChatAutomationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
