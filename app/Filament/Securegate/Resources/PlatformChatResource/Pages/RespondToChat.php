<?php

namespace App\Filament\Securegate\Resources\PlatformChatResource\Pages;

use App\Filament\Securegate\Resources\PlatformChatResource;
use Filament\Resources\Pages\EditRecord;

class RespondToChat extends EditRecord
{
    protected static string $resource = PlatformChatResource::class;

    protected string $view = 'filament.securegate.resources.platform-chat-resource.pages.respond-to-chat';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
