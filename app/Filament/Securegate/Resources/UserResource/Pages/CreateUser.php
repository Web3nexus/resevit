<?php

namespace App\Filament\Securegate\Resources\UserResource\Pages;

use App\Filament\Securegate\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
