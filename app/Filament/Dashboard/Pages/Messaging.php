<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\Social\SocialMessageRouterService;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class Messaging extends Page implements HasActions
{
    use InteractsWithActions;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static string|\UnitEnum|null $navigationGroup = 'Communication';

    protected static ?string $title = 'Unified Inbox';

    protected string $view = 'filament.dashboard.pages.messaging';

    public static function canAccess(): bool
    {
        return has_feature('messaging');
    }

    public $selectedChatId = null;
    public $newMessage = '';
    public $filter = 'all'; // all, open, closed

    protected function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            // In a real app, listen for Echo events: "echo-private:tenant.{id},.MessageReceived" => "handleIncomingMessage"
        ];
    }

    #[Computed]
    public function chats()
    {
        return Chat::query()
            ->when($this->filter !== 'all', fn($q) => $q->where('status', $this->filter))
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    #[Computed]
    public function activeChat()
    {
        if (!$this->selectedChatId)
            return null;
        return Chat::find($this->selectedChatId);
    }

    #[Computed]
    public function messages()
    {
        if (!$this->selectedChatId)
            return [];
        return ChatMessage::where('chat_id', $this->selectedChatId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function selectChat($id)
    {
        $this->selectedChatId = $id;
        // Mark as read logic would go here
        $chat = Chat::find($id);
        if ($chat) {
            $chat->update(['unread_count' => 0]);
        }
    }

    public function sendMessage(SocialMessageRouterService $router)
    {
        $this->validate([
            'newMessage' => 'required|string|max:2000',
        ]);

        $chat = $this->activeChat;
        if (!$chat)
            return;

        // Determine correct service based on chat source
        $service = $router->getService($chat->source, $this->getAccountForChat($chat));

        if ($service && $service->send($chat, $this->newMessage)) {
            $this->newMessage = '';
            $chat->touch('last_message_at');
            $this->dispatch('message-sent');
        } else {
            $this->addError('newMessage', 'Failed to send message via ' . $chat->source);
        }
    }

    public function createReservationAction(): Action
    {
        return Action::make('createReservation')
            ->label('Quick Reservation')
            ->color('success')
            ->icon('heroicon-o-calendar')
            ->form([
                TextInput::make('guest_name')
                    ->required(),
                TextInput::make('guest_phone'),
                DateTimePicker::make('reservation_time')
                    ->required()
                    ->seconds(false),
                Select::make('party_size')
                    ->options(array_combine(range(1, 20), range(1, 20)))
                    ->default(2)
                    ->required(),
                Select::make('source')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                    ])
                    ->default(fn() => $this->activeChat?->source ?? 'whatsapp')
                    ->required(),
            ])
            ->action(function (array $data) {
                \App\Models\Reservation::create([
                    'guest_name' => $data['guest_name'],
                    'guest_phone' => $data['guest_phone'],
                    'reservation_time' => $data['reservation_time'],
                    'party_size' => $data['party_size'],
                    'source' => $data['source'],
                    'status' => 'confirmed',
                ]);

                Notification::make()
                    ->success()
                    ->title('Reservation created successfully!')
                    ->send();
            });
    }

    protected function getAccountForChat(Chat $chat)
    {
        // Simple resolution: Find active account for this platform.
        // If multiple accounts per platform supported, logic needs to be enhanced 
        // to link Chat -> SocialAccount explicitly.
        return \App\Models\SocialAccount::where('platform', $chat->source)
            ->where('is_active', true)
            ->first();
    }
}
