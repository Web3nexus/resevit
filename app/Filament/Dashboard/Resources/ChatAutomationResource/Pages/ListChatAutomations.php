<?php

namespace App\Filament\Dashboard\Resources\ChatAutomationResource\Pages;

use App\Filament\Dashboard\Resources\ChatAutomationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChatAutomations extends ListRecords
{
    protected static string $resource = ChatAutomationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
