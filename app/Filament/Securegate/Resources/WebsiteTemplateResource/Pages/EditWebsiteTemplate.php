<?php

namespace App\Filament\Securegate\Resources\WebsiteTemplateResource\Pages;

use App\Filament\Securegate\Resources\WebsiteTemplateResource;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteTemplate extends EditRecord
{
    protected static string $resource = WebsiteTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
