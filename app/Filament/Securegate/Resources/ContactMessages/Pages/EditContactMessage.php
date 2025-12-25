<?php

namespace App\Filament\Securegate\Resources\ContactMessages\Pages;

use App\Filament\Securegate\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactMessage extends EditRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
