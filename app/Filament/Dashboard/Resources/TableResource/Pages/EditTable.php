<?php

namespace App\Filament\Dashboard\Resources\TableResource\Pages;

use App\Filament\Dashboard\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
