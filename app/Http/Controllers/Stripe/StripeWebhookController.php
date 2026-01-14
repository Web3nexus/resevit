<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Handle the Stripe webhook request.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        // We retrieve the secret from Cashier config
        $secret = config('cashier.webhook.secret');

        if (!$secret) {
            Log::error('Stripe Webhook Secret not configured.');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (SignatureVerificationException $e) {
            Log::warning('Invalid Stripe webhook signature detected.', [
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload.', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Process the verified event
        Log::info('Stripe webhook verified and received.', ['type' => $event->type]);

        // You can add specific event handlers here
        // For example:
        // if ($event->type === 'invoice.payment_succeeded') { ... }

        return response()->json(['status' => 'success']);
    }
}
