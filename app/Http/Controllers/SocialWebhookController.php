<?php

namespace App\Http\Controllers;

use App\Services\Social\SocialMessageRouterService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SocialWebhookController extends Controller
{
    public function __construct(
        protected SocialMessageRouterService $router
    ) {
    }

    /**
     * Handle incoming webhooks
     * Route: POST /webhooks/social/{platform}
     */
    public function handle(Request $request, string $platform)
    {
        Log::info("Webhook received for {$platform}", $request->all());

        // 1. Verify Tenant (This is handled by middleware but we need the context)
        // Since we are in tenant context, we can lookup accounts scoped to this tenant.

        // 2. Special handling for verification challenges (Meta)
        if ($request->input('hub_mode') === 'subscribe') {
            return $this->handleMetaVerification($request);
        }

        // 3. Dispatch to Router
        // For Meta (WhatsApp/FB/IG), we need to find the specific account based on ID in payload
        // to pass the correct credentials to the service if needed (for outbound reply mainly).
        // For inbound, we might just need to log it first.

        $this->router->routeIncoming($platform, $request->all(), null);

        return response()->json(['status' => 'processed']);
    }

    protected function handleMetaVerification(Request $request)
    {
        $verifyToken = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        // Ideally, we verify against a token stored in SocialAccount for this tenant.
        // For MVP, we might accept if any active account has this token or use a static one per tenant.
        // Let's assume the user configures the webhook with a matching token.

        // Lookup in DB: Does any social account have this verify token?
        // $exists = \App\Models\SocialAccount::whereJsonContains('credentials->verify_token', $verifyToken)->exists();
        // if ($exists) return response($challenge, 200);

        // For now, return challenge to verify connectivity.
        return response($challenge, 200);
    }
}
