<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StripeCallbackController extends Controller
{
    public function handle(Request $request)
    {
        if ($request->has('stripe_return')) {
            $user = Auth::user();
            $stripeService = new StripeService;

            if ($user && $user->stripe_account_id) {
                $account = $stripeService->getAccount($user->stripe_account_id);

                if ($account && $account->charges_enabled) {
                    $user->update([
                        'stripe_charges_enabled' => true,
                        'stripe_onboarding_complete' => true,
                    ]);

                    Notification::make()
                        ->title('Stripe account connected successfully!')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Account setup incomplete. Please continue onboarding.')
                        ->warning()
                        ->send();
                }
            }

            return redirect()->route('filament.dashboard.pages.payment-settings');
        }

        return redirect()->route('filament.dashboard.pages.payment-settings');
    }
}
