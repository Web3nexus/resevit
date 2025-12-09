<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class OAuthController extends Controller
{
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

        // Find or create user
        $user = User::where('email', $oauthUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $oauthUser->getName(),
                'email' => $oauthUser->getEmail(),
                'password' => bcrypt(str()->random(16)), // Random password for OAuth users
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
