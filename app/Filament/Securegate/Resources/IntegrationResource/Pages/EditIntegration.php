<?php

namespace App\Filament\Securegate\Resources\IntegrationResource\Pages;

use App\Filament\Securegate\Resources\IntegrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntegration extends EditRecord
{
    protected static string $resource = IntegrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
