<?php

namespace App\Services\Social;

use App\Models\AutomationFlow;
use App\Models\AutomationLog;
use App\Models\AutomationTrigger;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Log;

class AutomationEngineService
{
    /**
     * Process an incoming message for automation triggers or flow progression.
     */
    public function processIncoming(Chat $chat, ChatMessage $message): void
    {
        // 1. Check if chat is already in an active flow
        $activeLog = AutomationLog::where('chat_id', $chat->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        if ($activeLog) {
            $this->progressFlow($chat, $message, $activeLog);

            return;
        }

        // 2. If not in a flow, check for triggers
        $this->checkForTriggers($chat, $message);
    }

    /**
     * Check if the message content matches any keyword triggers.
     */
    protected function checkForTriggers(Chat $chat, ChatMessage $message): void
    {
        $content = strtolower(trim($message->content));

        $trigger = AutomationTrigger::where('trigger_key', 'keyword')
            ->where('trigger_value', $content)
            ->with('flow')
            ->first();

        if ($trigger && $trigger->flow && $trigger->flow->is_active) {
            $this->startFlow($chat, $trigger->flow);
        }
    }

    /**
     * Start a new automation flow for the chat.
     */
    public function startFlow(Chat $chat, AutomationFlow $flow): void
    {
        $log = AutomationLog::create([
            'chat_id' => $chat->id,
            'automation_flow_id' => $flow->id,
            'step_index' => 0,
            'status' => 'in_progress',
            'metadata' => [],
        ]);

        $this->executeStep($chat, $flow, 0, $log);
    }

    /**
     * Progress an existing flow based on user input.
     */
    protected function progressFlow(Chat $chat, ChatMessage $message, AutomationLog $log): void
    {
        $flow = $log->flow;
        $nextStepIndex = $log->step_index + 1;

        if (isset($flow->steps[$nextStepIndex])) {
            $log->update(['step_index' => $nextStepIndex]);
            $this->executeStep($chat, $flow, $nextStepIndex, $log);
        } else {
            $log->update(['status' => 'completed']);
        }
    }

    /**
     * Execute a specific step in the flow.
     */
    protected function executeStep(Chat $chat, AutomationFlow $flow, int $stepIndex, AutomationLog $log): void
    {
        $step = $flow->steps[$stepIndex] ?? null;

        if (! $step) {
            $log->update(['status' => 'completed']);

            return;
        }

        $type = $step['type'] ?? 'message';

        switch ($type) {
            case 'message':
                $this->sendMessage($chat, $step['content'] ?? '');
                break;

            case 'action':
                $this->performAction($chat, $step['action'] ?? '', $step['params'] ?? [], $log);
                break;
        }

        // If it's just a message and there's no "wait_for_input" flag,
        // we might want to auto-progress or wait. For now, let's assume
        // each message step waits for a reply unless specified.
        if (isset($step['auto_progress']) && $step['auto_progress']) {
            $this->progressFlow($chat, new ChatMessage, $log);
        }
    }

    /**
     * Send a message through the appropriate social service.
     */
    protected function sendMessage(Chat $chat, string $content): void
    {
        $router = app(SocialMessageRouterService::class);
        // We need an account context. In tenant context, we can find the account by source.
        $account = \App\Models\SocialAccount::where('platform', $chat->source)
            ->where('is_active', true)
            ->first();

        if ($account) {
            $service = $router->getService($chat->source, $account);
            if ($service && method_exists($service, 'send')) {
                $service->send($chat, $content);
            }
        }
    }

    /**
     * Perform a system action (e.g., create booking).
     */
    protected function performAction(Chat $chat, string $action, array $params, AutomationLog $log): void
    {
        switch ($action) {
            case 'create_reservation':
                // Integration with ReservationService would go here
                Log::info("Automation Action: Creating reservation for chat {$chat->id}");
                break;

            case 'tag_chat':
                // Logic to tag chat
                break;
        }
    }
}
