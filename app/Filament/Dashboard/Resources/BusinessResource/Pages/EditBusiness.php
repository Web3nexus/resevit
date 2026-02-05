<?php

namespace App\Filament\Dashboard\Resources\BusinessResource\Pages;

use App\Filament\Dashboard\Resources\BusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\MaxWidth;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
