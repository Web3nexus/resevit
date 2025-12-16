<?php

namespace App\Filament\Securegate\Resources\CustomerResource\Pages;

use App\Filament\Securegate\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
