<?php

namespace App\Filament\Securegate\Resources\BusinessCategories\Pages;

use App\Filament\Securegate\Resources\BusinessCategories\BusinessCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusinessCategory extends CreateRecord
{
    protected static string $resource = BusinessCategoryResource::class;
}
