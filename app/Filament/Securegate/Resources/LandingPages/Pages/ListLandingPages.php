<?php

namespace App\Filament\Securegate\Resources\LandingPages\Pages;

use App\Filament\Securegate\Resources\LandingPages\LandingPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLandingPages extends ListRecords
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
