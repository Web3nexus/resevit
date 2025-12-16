<?php

namespace App\Filament\Securegate\Resources\CustomerResource\Pages;

use App\Filament\Securegate\Resources\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
