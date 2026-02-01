<?php

namespace App\Services\Social;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBusinessService
{
    protected ?SocialAccount $account;

    public function __construct(?SocialAccount $account = null)
    {
        $this->account = $account;
    }

    /**
     * Handle incoming webhook payload from Google Business Messages
     */
    public function handleIncoming(array $payload): void
    {
        // Google Business Messages structure
        $message = $payload['message'] ?? [];
        $conversationId = $payload['conversationId'] ?? null;
        $senderId = $message['senderId'] ?? null;

        if (! $conversationId || ! $senderId) {
            return;
        }

        $text = $message['text'] ?? '';
        $messageId = $message['messageId'] ?? null;
        $displayName = $payload['context']['userInfo']['displayName'] ?? 'Google User';

        // Find or create Chat
        $chat = Chat::firstOrCreate(
            [
                'source' => 'google',
                'external_chat_id' => $conversationId,
            ],
            [
                'customer_name' => $displayName,
                'customer_handle' => $senderId,
                'status' => 'open',
            ]
        );

        // Save Message
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'direction' => 'inbound',
            'content' => $text,
            'external_message_id' => $messageId,
            'metadata' => $payload,
            'status' => 'delivered',
        ]);

        // Update Chat timestamp
        $chat->touch('last_message_at');
        $chat->increment('unread_count');

        // Pass to Automation Engine
        app(AutomationEngineService::class)->processIncoming($chat, $chatMessage);
    }

    /**
     * Send outbound message
     */
    public function send(Chat $chat, string $content): bool
    {
        if (! $this->account) {
            Log::error('GoogleBusinessService: No account context for sending.');

            return false;
        }

        $creds = $this->account->credentials;
        $agentId = $this->account->external_account_id;
        $token = $creds['access_token'] ?? null;

        if (! $token) {
            Log::error('GoogleBusinessService: Missing access token.');

            return false;
        }

        try {
            // Google Business Messages API
            $response = Http::withToken($token)
                ->post("https://businessmessages.googleapis.com/v1/conversations/{$chat->external_chat_id}/messages", [
                    'messageId' => \Illuminate\Support\Str::uuid(),
                    'representative' => [
                        'representativeType' => 'BOT',
                    ],
                    'text' => $content,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $messageId = $data['name'] ?? null;

                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'direction' => 'outbound',
                    'content' => $content,
                    'external_message_id' => $messageId,
                    'status' => 'sent',
                ]);

                return true;
            }

            Log::error('Google Business Message send failed: '.$response->body());

            return false;

        } catch (\Exception $e) {
            Log::error('Google Business Message send exception: '.$e->getMessage());

            return false;
        }
    }
}
