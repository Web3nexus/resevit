<?php

namespace App\Filament\Dashboard\Resources\SocialAccountResource\Pages;

use App\Filament\Dashboard\Resources\SocialAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialAccount extends EditRecord
{
    protected static string $resource = SocialAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
