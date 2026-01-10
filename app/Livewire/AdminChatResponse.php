<?php

namespace App\Livewire;

use App\Models\PlatformConversation;
use App\Models\PlatformMessage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminChatResponse extends Component
{
    public PlatformConversation $conversation;
    public $message = '';

    public function mount(PlatformConversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function sendMessage()
    {
        if (empty(trim($this->message)))
            return;

        try {
            PlatformMessage::on('landlord')->create([
                'platform_conversation_id' => $this->conversation->id,
                'sender_type' => 'admin',
                'sender_id' => auth()->id(),
                'body' => $this->message,
            ]);

            $this->conversation->update([
                'last_message_at' => now(),
            ]);

            $this->message = '';
            $this->dispatch('messageSent');
        } catch (\Exception $e) {
            Log::error('AdminChatResponse: Failed to send message', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function closeChat()
    {
        $this->conversation->update(['status' => 'closed']);
        return redirect()->to(\App\Filament\Securegate\Resources\PlatformChatResource::getUrl('index'));
    }

    public function render()
    {
        return view('livewire.admin-chat-response', [
            'messages' => $this->conversation->messages()->oldest()->get(),
        ]);
    }
}
