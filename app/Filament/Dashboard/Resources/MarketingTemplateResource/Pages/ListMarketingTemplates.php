<?php

namespace App\Filament\Dashboard\Resources\MarketingTemplateResource\Pages;

use App\Filament\Dashboard\Resources\MarketingTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class ListMarketingTemplates extends ListRecords
{
    protected static string $resource = MarketingTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
