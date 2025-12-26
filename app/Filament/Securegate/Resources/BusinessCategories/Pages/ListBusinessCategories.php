<?php

namespace App\Filament\Securegate\Resources\BusinessCategories\Pages;

use App\Filament\Securegate\Resources\BusinessCategories\BusinessCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusinessCategories extends ListRecords
{
    protected static string $resource = BusinessCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
