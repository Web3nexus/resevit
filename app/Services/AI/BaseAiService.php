<?php

namespace App\Services\AI;

use App\Models\AiSetting;
use OpenAI;
use Illuminate\Support\Facades\Log;

abstract class BaseAiService
{
    protected $client;
    protected $settings;

    public function __construct()
    {
        // Get active AI settings from landlord DB
        $this->settings = AiSetting::where('is_active', true)->first();

        if ($this->settings && $this->settings->api_key) {
            $this->client = OpenAI::client($this->settings->api_key);
        }
    }

    /**
     * Common safety check for client availability
     */
    protected function ensureClient(): bool
    {
        if (!$this->client) {
            Log::warning(get_class($this) . ": OpenAI client is not configured.");
            return false;
        }
        return true;
    }
}
