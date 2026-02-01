<?php

namespace App\Services\Social;

use App\Models\SocialAccount;
use Illuminate\Support\Facades\Log;

class SocialMessageRouterService
{
    /**
     * Route incoming webhook to appropriate service
     */
    public function routeIncoming(string $platform, array $payload, ?SocialAccount $account): void
    {
        try {
            $service = $this->getService($platform, $account);
            if ($service) {
                $service->handleIncoming($payload);
            }
        } catch (\Exception $e) {
            Log::error("Social routing failed for {$platform}: ".$e->getMessage());
        }
    }

    /**
     * Factory method to get platform service
     */
    public function getService(string $platform, ?SocialAccount $account = null): ?object
    {
        return match ($platform) {
            'whatsapp' => new WhatsAppService($account),
            'facebook' => new FacebookMessengerService($account),
            'instagram' => new InstagramDMService($account),
            'google' => new GoogleBusinessService($account),
            default => null,
        };
    }
}
