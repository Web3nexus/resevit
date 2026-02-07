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
        // For MVP/Chat system, showing all active chats in the tenant
        $chats = Chat::latest('last_message_at')
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
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string',
        ]);

        $sender = $request->user();

        DB::beginTransaction();
        try {
            $chat = Chat::findOrFail($validated['chat_id']);

            $message = $chat->messages()->create([
                'direction' => 'outbound',
                'content' => $validated['message'],
                'status' => 'sent',
            ]);

            $chat->update([
                'last_message_at' => now(),
            ]);

            // 1. Send the message to the social platform
            $router = app(\App\Services\Social\SocialMessageRouterService::class);
            $account = \App\Models\SocialAccount::where('platform', $chat->source)
                ->where('is_active', true)
                ->first();

            if ($account) {
                $service = $router->getService($chat->source, $account);
                if ($service && method_exists($service, 'send')) {
                    $service->send($chat, $validated['message']);
                }
            }

            DB::commit();

            return response()->json([
                'data' => $message
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to send message: ' . $e->getMessage()], 500);
        }
    }
}
