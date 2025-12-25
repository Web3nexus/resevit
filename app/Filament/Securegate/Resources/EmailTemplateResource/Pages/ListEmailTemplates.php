<?php

namespace App\Filament\Securegate\Resources\EmailTemplateResource\Pages;

use App\Filament\Securegate\Resources\EmailTemplateResource;
use Filament\Resources\Pages\ListRecords;

class ListEmailTemplates extends ListRecords
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
