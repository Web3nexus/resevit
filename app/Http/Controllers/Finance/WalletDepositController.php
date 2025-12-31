<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class WalletDepositController extends Controller
{
    /**
     * Redirect to Stripe Checkout for deposit.
     */
    public function deposit(Request $request)
    {
        $user = Auth::user();

        // Load platform Stripe settings if available
        $settings = \App\Models\PlatformSetting::current()->stripe_settings;
        if ($settings && !empty($settings['secret_key'])) {
            config(['cashier.secret' => $settings['secret_key']]);
            config(['cashier.key' => $settings['publishable_key'] ?? '']);
        }

        // In a real application, you would ask for the amount.
        // For now, we'll use a fixed amount or a query param.
        $amount = $request->query('amount', 500); // Default to $500
        $amountCents = $amount * 100;

        try {
            return $user->checkout([
                [
                    'price_data' => [
                        'currency' => $user->currency ?? 'usd',
                        'product_data' => [
                            'name' => 'Wallet Deposit',
                        ],
                        'unit_amount' => (int) $amountCents,
                    ],
                    'quantity' => 1,
                ],
            ], [
                'success_url' => route('wallet.deposit.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('filament.dashboard.pages.wallet'),
            ]);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return redirect()->route('filament.dashboard.pages.wallet')
                ->with('error', 'Stripe authentication failed. Administrators: Please verify the API keys in **Securegate > Platform Settings > Stripe Configuration**. Users: Please contact the platform administrator.');
        } catch (\Exception $e) {
            return redirect()->route('filament.dashboard.pages.wallet')
                ->with('error', 'An error occurred while processing your payment. Administrators: Please check the Stripe configuration in the **Securegate** panel. Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle success callback from Stripe.
     */
    public function success(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('filament.dashboard.pages.wallet');
        }

        // Verify session with Stripe (omitted for brevity, assuming success for now)
        // In production, use webhooks to confirm payment.

        // For demonstration, we'll manually top up the wallet here
        // (This should be in a webhook in a real app)

        $amount = 500; // This should be retrieved from session

        $user->increment('wallet_balance', $amount);

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'deposit',
            'status' => 'completed',
            'description' => 'Wallet Top-up via Stripe',
            'metadata' => ['stripe_session_id' => $sessionId]
        ]);

        return redirect()->route('filament.dashboard.pages.wallet')
            ->with('status', 'Wallet successfully topped up!');
    }
}
