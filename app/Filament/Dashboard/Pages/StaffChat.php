<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\StaffConversation;
use App\Models\StaffMessage;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class StaffChat extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string|\UnitEnum|null $navigationGroup = 'Communication';
    protected static ?string $title = 'Staff Chat';
    protected string $view = 'filament.dashboard.pages.staff-chat';

    public static function canAccess(): bool
    {
        return has_feature('staff_chat');
    }

    public $selectedConversationId = null;
    public $newMessage = '';
    public $searchQuery = '';

    public function mount()
    {
        // Auto-select first conversation or general group
        $first = $this->conversations->first();
        if ($first) {
            $this->selectConversation($first->id);
        }
    }

    #[Computed]
    public function conversations()
    {
        // Fetch conversations user is part of. 
        // For MVP, let's just show a "General" group chat accessible to everyone if it exists,
        // or create one if not.

        $general = StaffConversation::firstOrCreate(
            ['name' => 'General', 'type' => 'group']
        );

        // Ensure current user is participant
        if (!$general->participants()->where('user_id', Auth::id())->exists()) {
            $general->participants()->attach(Auth::id());
        }

        return StaffConversation::with('participants')->get();
    }

    #[Computed]
    public function activeConversation()
    {
        if (!$this->selectedConversationId)
            return null;
        return StaffConversation::find($this->selectedConversationId);
    }

    #[Computed]
    public function messages()
    {
        if (!$this->selectedConversationId)
            return [];
        return StaffMessage::where('staff_conversation_id', $this->selectedConversationId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    #[Computed]
    public function availableStaff()
    {
        // Explicitly get Staff members for this tenant
        // This ensures stricter isolation and relevance than just "all users"
        $staffUsers = \App\Models\Staff::with('user')->get()
            ->map(fn($staff) => $staff->user)
            ->filter();

        // Also include the Business Owner (who might not be in staff table)
        // We can find them by role or simply include all TenantUsers if we trust the DB isolation.
        // But to satisfy "select the staff", let's prioritize Staff.
        // If we want to enable chatting with the Owner, we should add them too.
        // Let's stick to Staff first as requested.

        return $staffUsers->where('id', '!=', Auth::id());
    }

    public function selectConversation($id)
    {
        $this->selectedConversationId = $id;
    }

    public function startConversation($targetUserId)
    {
        // Check if private conversation exists
        $conversation = StaffConversation::where('type', 'private')
            ->whereHas('participants', function ($query) use ($targetUserId) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('participants', function ($query) use ($targetUserId) {
                $query->where('user_id', $targetUserId);
            })
            ->first();

        if (!$conversation) {
            $targetUser = \App\Models\TenantUser::find($targetUserId);
            $conversation = StaffConversation::create([
                'type' => 'private',
                'name' => $targetUser->name, // Optional for private
            ]);

            $conversation->participants()->attach([Auth::id(), $targetUserId]);
        }

        $this->selectConversation($conversation->id);
    }

    public function sendMessage()
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            ['newMessage' => $this->newMessage],
            ['newMessage' => 'required|string|max:1000']
        );

        if ($validator->fails()) {
            $this->addError('newMessage', $validator->errors()->first('newMessage'));
            return;
        }

        if (!$this->selectedConversationId)
            return;

        StaffMessage::create([
            'staff_conversation_id' => $this->selectedConversationId,
            'sender_id' => Auth::id(),
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';

        // Scroll to bottom dispatch
        $this->dispatch('message-sent');
    }
}
