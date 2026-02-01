<?php

namespace App\Filament\Dashboard\Pages;

use App\Services\StripeService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PaymentSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected string $view = 'filament.dashboard.pages.payment-settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    public $stripeAccountStatus = 'not_connected'; // not_connected, pending, active

    public function mount()
    {
        $user = Auth::user();
        if ($user->stripe_charges_enabled) {
            $this->stripeAccountStatus = 'active';
        } elseif ($user->stripe_account_id) {
            $this->stripeAccountStatus = 'pending';
        }
    }

    public function connectStripe()
    {
        $user = Auth::user();
        $stripeService = new StripeService;

        try {
            if (! $user->stripe_account_id) {
                $account = $stripeService->createExpressAccount($user->email);
                if (! $account) {
                    Notification::make()->title('Failed to create Stripe account')->danger()->send();

                    return;
                }
                $user->update(['stripe_account_id' => $account->id]);
            }

            $link = $stripeService->createAccountLink(
                $user->stripe_account_id,
                route('filament.dashboard.pages.payment-settings'), // Refresh URL
                route('filament.dashboard.pages.payment-settings').'?stripe_return=true' // Return URL
            );

            if (! $link) {
                Notification::make()->title('Failed to create onboarding link')->danger()->send();

                return;
            }

            return redirect()->away($link->url);

        } catch (\Exception $e) {
            Notification::make()->title('Error connecting to Stripe: '.$e->getMessage())->danger()->send();
        }
    }

    public function checkStatus()
    {
        $user = Auth::user();
        if (! $user->stripe_account_id) {
            return;
        }

        $stripeService = new StripeService;
        $account = $stripeService->getAccount($user->stripe_account_id);

        if ($account && $account->charges_enabled) {
            $user->update([
                'stripe_charges_enabled' => true,
                'stripe_onboarding_complete' => true,
            ]);
            $this->stripeAccountStatus = 'active';
            Notification::make()->title('Stripe account connected successfully!')->success()->send();
        } else {
            Notification::make()->title('Account setup incomplete. Please continue onboarding.')->warning()->send();
        }
    }
}
