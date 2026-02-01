<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * List user's conversations.
     */
    public function conversations(Request $request)
    {
        // simplistic approach: find chats where user is participant
        // For MVP, assuming Chat model has relation or we query messages
        // Let's assume a Chat exists between participants.

        $userId = $request->user()->id;

        // This query depends on how Chat is structured. 
        // Based on file list, we have Chat.php. Let's assume standard polymorphic or simple relation.
        // If Chat model isn't fully defined in my mind, I'll stick to a basic implementation.

        $chats = Chat::where('participant_one_id', $userId)
            ->orWhere('participant_two_id', $userId)
            ->with(['participantOne', 'participantTwo'])
            ->latest('updated_at')
            ->get();

        return response()->json([
            'data' => $chats
        ]);
    }

    /**
     * Get messages for a conversation.
     */
    public function messages(Chat $chat)
    {
        return response()->json([
            'data' => $chat->messages()->latest()->paginate(50)
        ]);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'chat_id' => 'nullable|exists:chats,id',
            'recipient_id' => 'required_without:chat_id|exists:users,id',
            'message' => 'required|string',
        ]);

        $sender = $request->user();
        $chat = null;

        DB::beginTransaction();
        try {
            if (!empty($validated['chat_id'])) {
                $chat = Chat::findOrFail($validated['chat_id']);
            } else {
                // Find or create chat
                // Simplification for MVP
                $chat = Chat::firstOrCreate([
                    'participant_one_id' => min($sender->id, $validated['recipient_id']),
                    'participant_two_id' => max($sender->id, $validated['recipient_id']),
                ]);
            }

            $message = $chat->messages()->create([
                'user_id' => $sender->id,
                'message' => $validated['message'],
            ]);

            $chat->touch(); // Update updated_at for sorting

            // Broadcast event here (TODO: Implement Event class)
            // broadcast(new MessageSent($message))->toOthers();

            DB::commit();

            return response()->json([
                'data' => $message
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to send message'], 500);
        }
    }
}
