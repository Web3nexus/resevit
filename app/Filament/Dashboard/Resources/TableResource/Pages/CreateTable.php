<?php

namespace App\Filament\Dashboard\Resources\TableResource\Pages;

use App\Filament\Dashboard\Resources\TableResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTable extends CreateRecord
{
    protected static string $resource = TableResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
