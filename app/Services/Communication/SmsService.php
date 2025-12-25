<?php

namespace App\Services\Communication;

use App\Models\SmsSetting;
use App\Models\DefaultSmsSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send an SMS using the configured provider
     */
    public function send(string $to, string $message): bool
    {
        try {
            // Get SMS configuration
            $config = $this->getSmsConfig();

            if (!$config) {
                Log::error('No SMS configuration available');
                return false;
            }

            // Send based on provider
            return match ($config['provider']) {
                'twilio' => $this->sendViaTwilio($to, $message, $config),
                'vonage' => $this->sendViaVonage($to, $message, $config),
                'messagebird' => $this->sendViaMessageBird($to, $message, $config),
                'plivo' => $this->sendViaPlivo($to, $message, $config),
                default => false,
            };

        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get SMS configuration (tenant or default)
     */
    protected function getSmsConfig(): ?array
    {
        // Try to get tenant-specific settings
        $tenantSettings = SmsSetting::where('is_active', true)->first();

        if ($tenantSettings && !$tenantSettings->use_default) {
            return [
                'provider' => $tenantSettings->provider,
                'api_key' => $tenantSettings->api_key,
                'api_secret' => $tenantSettings->api_secret,
                'from_number' => $tenantSettings->from_number,
                'api_region' => $tenantSettings->api_region,
            ];
        }

        // Fall back to default settings
        $defaultSettings = DefaultSmsSetting::where('is_active', true)->first();

        if (!$defaultSettings) {
            return null;
        }

        return [
            'provider' => $defaultSettings->provider,
            'api_key' => $defaultSettings->api_key,
            'api_secret' => $defaultSettings->api_secret,
            'from_number' => $defaultSettings->from_number,
            'api_region' => $defaultSettings->api_region,
        ];
    }

    /**
     * Send SMS via Twilio
     */
    protected function sendViaTwilio(string $to, string $message, array $config): bool
    {
        $response = Http::asForm()
            ->withBasicAuth($config['api_key'], $config['api_secret'])
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$config['api_key']}/Messages.json", [
                'From' => $config['from_number'],
                'To' => $to,
                'Body' => $message,
            ]);

        return $response->successful();
    }

    /**
     * Send SMS via Vonage (Nexmo)
     */
    protected function sendViaVonage(string $to, string $message, array $config): bool
    {
        $response = Http::post('https://rest.nexmo.com/sms/json', [
            'api_key' => $config['api_key'],
            'api_secret' => $config['api_secret'],
            'from' => $config['from_number'],
            'to' => $to,
            'text' => $message,
        ]);

        return $response->successful();
    }

    /**
     * Send SMS via MessageBird
     */
    protected function sendViaMessageBird(string $to, string $message, array $config): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'AccessKey ' . $config['api_key'],
        ])->post('https://rest.messagebird.com/messages', [
            'originator' => $config['from_number'],
            'recipients' => [$to],
            'body' => $message,
        ]);

        return $response->successful();
    }

    /**
     * Send SMS via Plivo
     */
    protected function sendViaPlivo(string $to, string $message, array $config): bool
    {
        $authId = $config['api_key'];
        $authToken = $config['api_secret'];

        $response = Http::withBasicAuth($authId, $authToken)
            ->post("https://api.plivo.com/v1/Account/{$authId}/Message/", [
                'src' => $config['from_number'],
                'dst' => $to,
                'text' => $message,
            ]);

        return $response->successful();
    }
}
