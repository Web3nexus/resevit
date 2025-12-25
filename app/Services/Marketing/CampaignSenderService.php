<?php

namespace App\Services\Marketing;

use App\Models\MarketingCampaign;
use Illuminate\Support\Facades\Log;

class CampaignSenderService
{
    public function send(MarketingCampaign $campaign): bool
    {
        // Update status to prevent double send
        $campaign->update(['status' => 'sending']);

        try {
            // Stub logic: Log the sending
            Log::info("Sending Campaign [ID: {$campaign->id}, Name: {$campaign->name}] via {$campaign->type}");
            
            // Simulation delay
            // sleep(1);

            // Mark as sent
            $campaign->update([
                'status' => 'sent',
                'sent_at' => now(),
                'stats' => [
                    'recipient_count' => 50, // Mock count
                    'delivered' => 50,
                ]
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send campaign {$campaign->id}: " . $e->getMessage());
            $campaign->update(['status' => 'failed']);
            return false;
        }
    }
}
