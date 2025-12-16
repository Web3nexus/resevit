<?php

namespace App\Filament\Securegate\Resources\AdminResource\Pages;

use App\Filament\Securegate\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->guard('securegate')->user()?->hasRole('securegate_admin')),
        ];
    }
}
