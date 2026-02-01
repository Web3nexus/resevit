<?php

namespace App\Services\Social;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramDMService
{
    protected ?SocialAccount $account;

    public function __construct(?SocialAccount $account = null)
    {
        $this->account = $account;
    }

    public function handleIncoming(array $payload): void
    {
        // Instagram structure is similar to Messenger but under 'instagram' object usually
        // or same 'messaging' structure if using Instagram Graph API for messages

        $entry = $payload['entry'][0] ?? [];
        $messaging = $entry['messaging'][0] ?? [];

        $senderId = $messaging['sender']['id'] ?? null;
        $message = $messaging['message'] ?? null;

        if (! $senderId || ! $message) {
            return;
        }

        $text = $message['text'] ?? '[Media]';
        $messageId = $message['mid'] ?? null;

        // Placeholder name
        $customerName = "Instagram User ({$senderId})";

        $chat = Chat::firstOrCreate(
            [
                'source' => 'instagram',
                'external_chat_id' => $senderId,
            ],
            [
                'customer_name' => $customerName,
                'customer_handle' => $senderId,
                'status' => 'open',
            ]
        );

        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'direction' => 'inbound',
            'content' => $text,
            'external_message_id' => $messageId,
            'metadata' => $messaging,
            'status' => 'delivered',
        ]);

        $chat->touch('last_message_at');
        $chat->increment('unread_count');

        // Pass to Automation Engine
        app(AutomationEngineService::class)->processIncoming($chat, $chatMessage);
    }

    public function send(Chat $chat, string $content): bool
    {
        if (! $this->account) {
            return false;
        }

        $creds = $this->account->credentials;
        $token = $creds['access_token'] ?? null;

        if (! $token) {
            return false;
        }

        try {
            // Instagram Messaging API
            $response = Http::withToken($token)
                ->post('https://graph.facebook.com/v19.0/me/messages', [
                    'recipient' => ['id' => $chat->external_chat_id],
                    'message' => ['text' => $content],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'direction' => 'outbound',
                    'content' => $content,
                    'external_message_id' => $data['message_id'] ?? null,
                    'status' => 'sent',
                ]);

                return true;
            }

            Log::error('IG DM send failed: '.$response->body());

            return false;

        } catch (\Exception $e) {
            Log::error('IG DM send exception: '.$e->getMessage());

            return false;
        }
    }
}
