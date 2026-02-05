<?php

namespace App\Filament\Dashboard\Resources\BusinessResource\Pages;

use App\Filament\Dashboard\Resources\BusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
