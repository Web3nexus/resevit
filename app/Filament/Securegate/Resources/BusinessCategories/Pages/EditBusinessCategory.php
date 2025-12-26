<?php

namespace App\Filament\Securegate\Resources\BusinessCategories\Pages;

use App\Filament\Securegate\Resources\BusinessCategories\BusinessCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBusinessCategory extends EditRecord
{
    protected static string $resource = BusinessCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
