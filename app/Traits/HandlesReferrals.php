<?php

namespace App\Traits;

use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Models\Influencer;
use App\Models\User;
use App\Models\Customer;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Log;

trait HandlesReferrals
{
    protected function applyReferral($newModel): void
    {
        $referralCode = session('referral_code');

        if (!$referralCode) {
            return;
        }

        try {
            $referrer = $this->findReferrerByCode($referralCode);

            if ($referrer) {
                $referral = Referral::create([
                    'referrer_id' => $referrer->id,
                    'referrer_type' => get_class($referrer),
                    'referral_code' => $referralCode,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'converted_at' => now(),
                ]);

                $this->calculateCommission($referral, $referrer);

                // Optional: Clear session after conversion?
                // session()->forget('referral_code');
            }
        } catch (\Exception $e) {
            Log::error('Failed to create referral record: ' . $e->getMessage());
        }
    }

    protected function findReferrerByCode(string $code)
    {
        if ($influencer = Influencer::where('referral_code', $code)->first()) {
            return $influencer;
        }

        if ($user = User::where('referral_code', $code)->first()) {
            return $user;
        }

        if ($customer = Customer::where('referral_code', $code)->first()) {
            return $customer;
        }

        return null;
    }

    protected function calculateCommission(Referral $referral, $referrer): void
    {
        $settings = PlatformSetting::current();
        if (!$settings)
            return;

        $promo = $settings->promotion_settings;
        $amount = 0;
        $enabled = false;

        if ($referrer instanceof Influencer) {
            $enabled = true; // Influencers always enabled by default if system is on
            $amount = $promo['influencer_commission'] ?? 0;
        } elseif ($referrer instanceof User) {
            $enabled = $promo['owner_referral_enabled'] ?? false;
            $amount = $promo['owner_commission'] ?? 0;
        } elseif ($referrer instanceof Customer) {
            $enabled = $promo['customer_referral_enabled'] ?? false;
            $amount = $promo['customer_commission'] ?? 0;
        }

        if ($enabled && $amount > 0) {
            ReferralEarning::create([
                'earner_id' => $referrer->id,
                'earner_type' => get_class($referrer),
                'referral_id' => $referral->id,
                'amount' => $amount,
                'currency' => 'USD', // Default or from settings
                'status' => 'pending',
                'description' => 'Referral commission for signup',
            ]);
        }
    }
}
