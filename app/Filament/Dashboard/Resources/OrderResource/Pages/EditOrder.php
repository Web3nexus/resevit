<?php

namespace App\Filament\Dashboard\Resources\OrderResource\Pages;

use App\Filament\Dashboard\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    // No delete action by default to preserve audit
}
