<?php

namespace App\Filament\Dashboard\Resources\ReservationResource\Pages;

use App\Filament\Dashboard\Resources\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
