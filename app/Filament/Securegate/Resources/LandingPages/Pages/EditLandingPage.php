<?php

namespace App\Filament\Securegate\Resources\LandingPages\Pages;

use App\Filament\Securegate\Resources\LandingPages\LandingPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLandingPage extends EditRecord
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
