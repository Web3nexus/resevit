<?php

namespace App\Filament\Dashboard\Resources\CategoryResource\Pages;

use App\Filament\Dashboard\Resources\CategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
