<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Facades\Filament;
use Livewire\Component;

class TicketChat extends Component
{
    public Ticket $ticket;
    public $message = '';

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Filament::auth()->user();

        TicketMessage::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => $user->id,
            'user_type' => get_class($user),
            'message' => $this->message,
        ]);

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.ticket-chat', [
            'messages' => $this->ticket->messages()->with('sender')->latest()->get(),
        ]);
    }
}
