<?php

namespace App\Livewire;

use App\Models\PlatformConversation;
use App\Models\PlatformMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LiveChatWidget extends Component
{
    public $isOpen = false;
    public $message = '';
    public $conversationId;
    public $email = '';
    public $name = '';
    public $showEmailForm = false;

    public function mount()
    {
        $this->loadConversation();
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen && !$this->conversationId && !auth()->check()) {
            $this->showEmailForm = true;
        }
    }

    public function startConversation()
    {
        $this->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $conversation = PlatformConversation::on('landlord')->create([
            'session_id' => Session::getId(),
            'email' => $this->email,
            'name' => $this->name,
            'status' => 'open',
        ]);

        Log::info('LiveChat: Conversation created', ['id' => $conversation->id, 'session' => Session::getId()]);

        $this->conversationId = $conversation->id;
        $this->showEmailForm = false;

        // Send initial greeting from system/admin
        PlatformMessage::on('landlord')->create([
            'platform_conversation_id' => $this->conversationId,
            'sender_type' => 'admin',
            'body' => "Hi {$this->name}! How can we help you today?",
        ]);
    }

    public function sendMessage()
    {
        Log::info('LiveChat: sendMessage called', ['message' => $this->message, 'conversationId' => $this->conversationId]);
        if (empty(trim($this->message)))
            return;

        if (!$this->conversationId) {
            $this->loadConversation();
        }

        if (!$this->conversationId) {
            if (auth()->check()) {
                $user = auth()->user();
                $conversation = PlatformConversation::on('landlord')->create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'status' => 'open',
                ]);
                Log::info('LiveChat: Auth Conversation created', ['id' => $conversation->id, 'user_id' => $user->id]);
                $this->conversationId = $conversation->id;
            } else {
                $this->showEmailForm = true;
                return;
            }
        }

        PlatformMessage::on('landlord')->create([
            'platform_conversation_id' => $this->conversationId,
            'sender_type' => auth()->check() ? 'user' : 'guest',
            'sender_id' => auth()->id(),
            'body' => $this->message,
        ]);

        Log::info('LiveChat: Message created', ['conversationId' => $this->conversationId, 'sender' => auth()->id()]);

        PlatformConversation::find($this->conversationId)->update([
            'last_message_at' => now(),
        ]);

        $this->message = '';
        $this->dispatch('messageSent');
    }

    protected function loadConversation()
    {
        if (auth()->check()) {
            $conv = PlatformConversation::where('user_id', auth()->id())
                ->where('status', 'open')
                ->latest()
                ->first();
            $this->conversationId = $conv?->id;
        } else {
            $conv = PlatformConversation::where('session_id', Session::getId())
                ->where('status', 'open')
                ->latest()
                ->first();
            $this->conversationId = $conv?->id;
        }
    }

    public function render()
    {
        $messages = $this->conversationId
            ? PlatformMessage::where('platform_conversation_id', $this->conversationId)->oldest()->get()
            : collect();

        return view('livewire.live-chat-widget', [
            'messages' => $messages,
        ]);
    }
}
