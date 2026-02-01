<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\SocialAccount;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class Connections extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static string|UnitEnum|null $navigationGroup = 'Integrations';

    protected static ?string $navigationLabel = 'Connections';

    protected static ?string $title = 'Business Connections';

    protected string $view = 'filament.dashboard.pages.connections';

    public $socialAccounts;

    public function mount()
    {
        $this->refreshSocialAccounts();
    }

    public function refreshSocialAccounts()
    {
        $this->socialAccounts = SocialAccount::all()->keyBy('platform');
    }

    public function connectAction(string $platform)
    {
        return redirect()->route('social.connect', ['platform' => $platform]);
    }

    public function disconnectAction(string $platform)
    {
        SocialAccount::where('platform', $platform)->delete();
        $this->refreshSocialAccounts();

        Notification::make()
            ->title(ucfirst($platform).' disconnected.')
            ->success()
            ->send();
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('guide_whatsapp')
                ->label('WhatsApp Guide')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->modalHeading('Connecting WhatsApp Business')
                ->modalContent(view('filament.dashboard.pages.guides.whatsapp-guide'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->color('success'),

            Action::make('guide_facebook')
                ->label('Facebook Guide')
                ->icon('heroicon-m-hand-thumb-up')
                ->modalHeading('Connecting Facebook Page')
                ->modalContent(view('filament.dashboard.pages.guides.facebook-guide'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->color('primary'),

            Action::make('guide_instagram')
                ->label('Instagram Guide')
                ->icon('heroicon-m-camera')
                ->modalHeading('Connecting Instagram Business')
                ->modalContent(view('filament.dashboard.pages.guides.instagram-guide'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->color('warning'),

            Action::make('guide_google')
                ->label('Google Guide')
                ->icon('heroicon-m-globe-alt')
                ->modalHeading('Connecting Google Business')
                ->modalContent(view('filament.dashboard.pages.guides.google-guide'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->color('info'),
        ];
    }

    // We can add actions for each card to be triggered from the view
}
