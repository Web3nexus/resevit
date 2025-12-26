<?php

namespace App\Filament\Securegate\Resources\TenantResource\Pages;

use App\Filament\Securegate\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
