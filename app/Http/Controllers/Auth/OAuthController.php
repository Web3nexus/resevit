<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Traits\HandlesReferrals;

class OAuthController extends Controller
{
    use HandlesReferrals;
    /**
     * Redirect to OAuth provider
     */
    public function redirect(string $provider)
    {
        // Validate the provider
        if (!in_array($provider, ['google', 'facebook', 'apple'])) {
            return redirect('/login')->with('error', 'Invalid OAuth provider');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle OAuth callback
     */
    public function callback(string $provider)
    {
        try {
            $oauthUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'OAuth authentication failed');
        }

        $email = $oauthUser->getEmail();

        // 1. Check LandlordUser (Business Owner)
        $user = \App\Models\LandlordUser::where('email', $email)->first();
        if ($user) {
            Auth::guard('web')->login($user);
            return redirect()->intended('/dashboard');
        }

        // 2. Check Customer
        $customer = \App\Models\Customer::where('email', $email)->first();
        if ($customer) {
            Auth::guard('customer')->login($customer);
            return redirect()->route('customer.login'); // Or dashboard if exists
        }

        // 3. Check Investor
        $investor = \App\Models\Investor::where('email', $email)->first();
        if ($investor) {
            Auth::guard('investor')->login($investor);
            return redirect()->route('investor.login');
        }

        // 4. If not found, create new LandlordUser (Default)
        // Note: This user will NOT have a tenant yet.
        $newUser = \App\Models\LandlordUser::create([
            'name' => $oauthUser->getName(),
            'email' => $email,
            'password' => bcrypt(str()->random(16)),
            'email_verified_at' => now(),
            // Default flags
            'terms_accepted' => true,
            'newsletter_subscribed' => false,
        ]);

        $this->applyReferral($newUser);

        Auth::guard('web')->login($newUser);

        return redirect('/dashboard');
    }
}
