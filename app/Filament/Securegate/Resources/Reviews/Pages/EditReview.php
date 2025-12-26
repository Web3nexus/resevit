<?php

namespace App\Filament\Securegate\Resources\Reviews\Pages;

use App\Filament\Securegate\Resources\Reviews\ReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
