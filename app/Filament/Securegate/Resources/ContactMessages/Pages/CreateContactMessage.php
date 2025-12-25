<?php

namespace App\Filament\Securegate\Resources\ContactMessages\Pages;

use App\Filament\Securegate\Resources\ContactMessages\ContactMessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactMessage extends CreateRecord
{
    protected static string $resource = ContactMessageResource::class;
}
