<?php

namespace App\Filament\Securegate\Resources\Faqs\Pages;

use App\Filament\Securegate\Resources\Faqs\FaqResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
