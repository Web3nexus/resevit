<?php

namespace App\Services\Social;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?SocialAccount $account;

    public function __construct(?SocialAccount $account = null)
    {
        $this->account = $account;
    }

    /**
     * Handle incoming webhook payload
     */
    public function handleIncoming(array $payload): void
    {
        // Extract relevant data from WhatsApp payload
        $entry = $payload['entry'][0] ?? [];
        $changes = $entry['changes'][0] ?? [];
        $value = $changes['value'] ?? [];

        if (!isset($value['messages'][0])) {
            return; // Not a message event
        }

        $message = $value['messages'][0];
        $contact = $value['contacts'][0] ?? [];

        $from = $message['from']; // Phone number
        $text = $message['text']['body'] ?? '';
        $messageId = $message['id'];
        $senderName = $contact['profile']['name'] ?? $from;

        // Find or create Chat
        $chat = Chat::firstOrCreate(
            [
                'source' => 'whatsapp',
                'external_chat_id' => $from,
            ],
            [
                'customer_name' => $senderName,
                'customer_handle' => $from,
                'status' => 'open',
            ]
        );

        // Save Message
        ChatMessage::create([
            'chat_id' => $chat->id,
            'direction' => 'inbound',
            'content' => $text,
            'external_message_id' => $messageId,
            'metadata' => $message,
            'status' => 'delivered',
        ]);

        // Update Chat timestamp
        $chat->touch('last_message_at');
        $chat->increment('unread_count');
    }

    /**
     * Send outbound message
     */
    public function send(Chat $chat, string $content): bool
    {
        if (!$this->account) {
            Log::error('WhatsAppService: No account context for sending.');
            return false;
        }

        $creds = $this->account->credentials;
        $phoneId = $this->account->external_account_id;
        $token = $creds['access_token'] ?? null;

        if (!$token) {
            Log::error('WhatsAppService: Missing access token.');
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v19.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $chat->external_chat_id,
                    'type' => 'text',
                    'text' => ['body' => $content],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $messageId = $data['messages'][0]['id'] ?? null;

                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'direction' => 'outbound',
                    'content' => $content,
                    'external_message_id' => $messageId,
                    'status' => 'sent',
                ]);

                return true;
            }

            Log::error('WhatsApp send failed: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('WhatsApp send exception: ' . $e->getMessage());
            return false;
        }
    }
}
