<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createExpressAccount(string $email, string $country = 'US'): ?Account
    {
        try {
            return Account::create([
                'type' => 'express',
                'country' => $country,
                'email' => $email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Account Creation Failed: '.$e->getMessage());

            return null;
        }
    }

    public function createAccountLink(string $accountId, string $refreshUrl, string $returnUrl): ?AccountLink
    {
        try {
            return AccountLink::create([
                'account' => $accountId,
                'refresh_url' => $refreshUrl,
                'return_url' => $returnUrl,
                'type' => 'account_onboarding',
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Account Link Creation Failed: '.$e->getMessage());

            return null;
        }
    }

    public function getAccount(string $accountId): ?Account
    {
        try {
            return Account::retrieve($accountId);
        } catch (\Exception $e) {
            Log::error('Stripe Account Retrieval Failed: '.$e->getMessage());

            return null;
        }
    }

    public function createPaymentIntent(float $amount, string $currency = 'usd', ?string $connectedAccountId = null, float $applicationFee = 0): ?PaymentIntent
    {
        try {
            $params = [
                'amount' => (int) ($amount * 100), // Convert to cents
                'currency' => $currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ];

            if ($connectedAccountId) {
                // Destination charge - platform takes fee, rest goes to connected account
                $params['application_fee_amount'] = (int) ($applicationFee * 100);
                $params['transfer_data'] = [
                    'destination' => $connectedAccountId,
                ];
            }

            return PaymentIntent::create($params);
        } catch (\Exception $e) {
            Log::error('Stripe Payment Intent Creation Failed: '.$e->getMessage());

            return null;
        }
    }
}
