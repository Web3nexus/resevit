<?php

namespace App\Filament\Dashboard\Resources\SocialAccountResource\Pages;

use App\Filament\Dashboard\Resources\SocialAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialAccounts extends ListRecords
{
    protected static string $resource = SocialAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('connect_instagram')
                ->label('Connect Instagram')
                ->icon('heroicon-o-camera')
                ->color('warning')
                ->url(fn () => route('social.connect', ['platform' => 'instagram'])),
            Actions\Action::make('connect_whatsapp')
                ->label('Connect WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('success')
                ->url(fn () => route('social.connect', ['platform' => 'whatsapp'])),
            Actions\Action::make('connect_google')
                ->label('Connect Google')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->url(fn () => route('social.connect', ['platform' => 'google'])),
        ];
    }
}
