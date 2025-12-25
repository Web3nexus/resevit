<?php

namespace App\Filament\Securegate\Resources\LegalDocumentResource\Pages;

use App\Filament\Securegate\Resources\LegalDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalDocument extends EditRecord
{
    protected static string $resource = LegalDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
