<?php

namespace App\Filament\Securegate\Resources\WebsiteTemplateResource\Pages;

use App\Filament\Securegate\Resources\WebsiteTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteTemplates extends ListRecords
{
    protected static string $resource = WebsiteTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
