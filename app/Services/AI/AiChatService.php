<?php

namespace App\Services\AI;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Log;

class AiChatService extends BaseAiService
{
    /**
     * Get a response from the AI for a given chat and message.
     */
    public function getResponse(Chat $chat, ChatMessage $message): string
    {
        if (!$this->ensureClient()) {
            return "I'm sorry, I'm having trouble connecting to my brain right now. Please try again later.";
        }

        try {
            $history = $this->getConversationHistory($chat);
            $systemPrompt = $this->getSystemPrompt();

            $messages = array_merge(
                [['role' => 'system', 'content' => $systemPrompt]],
                $history,
                [['role' => 'user', 'content' => $message->content]]
            );

            $response = $this->client->chat()->create([
                'model' => $this->settings->chat_model ?? 'gpt-4o-mini',
                'messages' => $messages,
                'tools' => $this->getAvailableTools(),
                'tool_choice' => 'auto',
                'temperature' => 0.7,
            ]);

            $choice = $response->choices[0];

            if ($choice->finishReason === 'tool_calls') {
                return $this->handleToolCalls($chat, $choice->message->toolCalls);
            }

            return $choice->message->content;

        } catch (\Exception $e) {
            Log::error('AI Chat Response failed: ' . $e->getMessage());
            return "I'm sorry, I encountered an error processing your request.";
        }
    }

    /**
     * Retrieve recent conversation history for context.
     */
    protected function getConversationHistory(Chat $chat, int $limit = 10): array
    {
        return ChatMessage::where('chat_id', $chat->id)
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->map(function ($msg) {
                return [
                    'role' => $msg->direction === 'inbound' ? 'user' : 'assistant',
                    'content' => $msg->content,
                ];
            })
            ->toArray();
    }

    /**
     * Define the AI's personality and rules.
     */
    protected function getSystemPrompt(): string
    {
        $tenant = tenant();
        $name = $tenant->name ?? 'the restaurant';

        $prompt = "You are a helpful and professional AI assistant for {$name}. ";
        $prompt .= "Your goal is to assist customers with inquiries and help them make reservations. ";
        $prompt .= "Follow these rules:\n";
        $prompt .= "1. Be concise but friendly.\n";
        $prompt .= "2. If a customer wants to book a table, use the 'check_availability' tool first.\n";
        $prompt .= "3. If a table is available, use 'create_reservation' to confirm it.\n";
        $prompt .= "4. Provide accurate information based on the restaurant context.\n";
        $prompt .= "5. If you cannot fulfill a request, suggest contacting staff directly.";

        return $prompt;
    }

    /**
     * Define tools the AI can use.
     */
    protected function getAvailableTools(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'check_availability',
                    'description' => 'Check if a table is available for a specific time and party size.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'time' => [
                                'type' => 'string',
                                'description' => 'ISO 8601 formatted date and time (e.g. 2024-05-10T19:00:00)',
                            ],
                            'party_size' => [
                                'type' => 'integer',
                                'description' => 'Number of people in the party.',
                            ],
                        ],
                        'required' => ['time', 'party_size'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'create_reservation',
                    'description' => 'Create a reservation once availability is confirmed and customer is ready.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'time' => ['type' => 'string'],
                            'party_size' => ['type' => 'integer'],
                            'customer_name' => ['type' => 'string'],
                            'customer_email' => ['type' => 'string'],
                            'customer_phone' => ['type' => 'string'],
                        ],
                        'required' => ['time', 'party_size', 'customer_name'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Handle tool calls from the AI.
     */
    protected function handleToolCalls(Chat $chat, array $toolCalls): string
    {
        $reservationService = app(ReservationService::class);
        $results = [];

        foreach ($toolCalls as $call) {
            $name = $call->function->name;
            $args = json_decode($call->function->arguments, true);

            switch ($name) {
                case 'check_availability':
                    $time = \Carbon\Carbon::parse($args['time']);
                    $tables = $reservationService->checkAvailability($time, $args['party_size']);
                    $results[] = [
                        'tool_call_id' => $call->id,
                        'role' => 'tool',
                        'name' => $name,
                        'content' => $tables->count() > 0 ? "Tables available." : "No tables available at this time.",
                    ];
                    break;

                case 'create_reservation':
                    $reservation = $reservationService->createReservation([
                        'reservation_time' => \Carbon\Carbon::parse($args['time']),
                        'party_size' => $args['party_size'],
                        'customer_name' => $args['customer_name'] ?? $chat->customer_name,
                        'customer_email' => $args['customer_email'] ?? null,
                        'customer_phone' => $args['customer_phone'] ?? $chat->customer_handle,
                        'status' => 'confirmed',
                        'source' => 'ai_chat',
                    ]);
                    $results[] = [
                        'tool_call_id' => $call->id,
                        'role' => 'tool',
                        'name' => $name,
                        'content' => "Reservation created successfully! ID: {$reservation->id}",
                    ];
                    break;
            }
        }

        // After tool calls, we need to send the results back to get a final conversational response
        // For simplicity in this implementation, we'll trigger one more completion
        // In a more robust system, this would be a loop.

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->getSystemPrompt()]],
            $this->getConversationHistory($chat),
            $results
        );

        $finalResponse = $this->client->chat()->create([
            'model' => $this->settings->chat_model ?? 'gpt-4o-mini',
            'messages' => $messages,
        ]);

        return $finalResponse->choices[0]->message->content;
    }
}
