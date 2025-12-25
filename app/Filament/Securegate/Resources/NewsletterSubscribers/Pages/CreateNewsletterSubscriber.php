<?php

namespace App\Filament\Securegate\Resources\NewsletterSubscribers\Pages;

use App\Filament\Securegate\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsletterSubscriber extends CreateRecord
{
    protected static string $resource = NewsletterSubscriberResource::class;
}
