<?php

namespace App\Filament\Dashboard\Resources\OrderResource\Pages;

use App\Filament\Dashboard\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action manually, orders come from frontend
        ];
    }
}
