<?php

namespace App\Listeners;

use App\Models\PricingPlan;
use App\Models\Tenant;
// use Laravel\Cashier\Events\SubscriptionCreated;
// use Laravel\Cashier\Events\SubscriptionUpdated;
// use Laravel\Cashier\Events\SubscriptionDeleted;
use Illuminate\Support\Facades\Log;

class SubscriptionSyncListener
{
    /**
     * Handle subscription creation.
     */
    public function handleSubscriptionCreated(object $event): void
    {
        $this->syncPlan($event->subscription);
    }

    /**
     * Handle subscription update.
     */
    public function handleSubscriptionUpdated(object $event): void
    {
        $this->syncPlan($event->subscription);
    }

    /**
     * Handle subscription deletion.
     */
    public function handleSubscriptionDeleted(object $event): void
    {
        $tenant = $event->subscription->owner;

        if ($tenant instanceof Tenant) {
            Log::info("Stripe Subscription Deleted for Tenant: {$tenant->id}. Reverting to Starter plan.");

            $starterPlan = PricingPlan::where('slug', 'starter')->first();
            if ($starterPlan) {
                $tenant->update(['plan_id' => $starterPlan->id]);
            }
        }
    }

    /**
     * Sync the tenant's plan_id based on the Stripe Price ID.
     */
    protected function syncPlan($subscription): void
    {
        $tenant = $subscription->owner;

        if (!$tenant instanceof Tenant) {
            return;
        }

        // Cashier subscriptions can have multiple items, but usually we have one plan
        $stripePriceId = $subscription->stripe_price;

        if (!$stripePriceId) {
            return;
        }

        $plan = PricingPlan::where('stripe_id', $stripePriceId)->first();

        if ($plan) {
            if ($tenant->plan_id !== $plan->id) {
                Log::info("Syncing plan for Tenant: {$tenant->id} to Plan: {$plan->slug} (Price: {$stripePriceId})");
                $tenant->update(['plan_id' => $plan->id]);
            }
        } else {
            Log::warning("Could not find internal PricingPlan for Stripe Price: {$stripePriceId}");
        }
    }
}
