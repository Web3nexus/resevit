<?php

namespace App\Filament\Dashboard\Resources\NotificationTemplateResource\Pages;

use App\Filament\Dashboard\Resources\NotificationTemplateResource;
use Filament\Resources\Pages\ListRecords;

class ListNotificationTemplates extends ListRecords
{
    protected static string $resource = NotificationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
