<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialConnectionController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $platform)
    {
        $driver = $this->getDriverForPlatform($platform);

        return Socialite::driver($driver)
            ->scopes($this->getScopes($platform))
            ->redirect();
    }

    public function callback(string $platform)
    {
        try {
            $driver = $this->getDriverForPlatform($platform);
            $user = Socialite::driver($driver)->user();

            $idToStore = $user->getId();
            $tokenToStore = $user->token;
            $nameToStore = $user->getName() ?? $user->getNickname() ?? $platform.' Account';

            // Post-login: Try to fetch Pages/WABA details to be more useful
            if (in_array($platform, ['facebook', 'instagram'])) {
                $response = \Illuminate\Support\Facades\Http::get('https://graph.facebook.com/v19.0/me/accounts', [
                    'access_token' => $user->token,
                ]);

                if ($response->successful()) {
                    $pages = $response->json()['data'] ?? [];
                    if (count($pages) > 0) {
                        // Auto-select the first page for now
                        // TODO: Implement a selection UI in the future
                        $page = $pages[0];
                        $idToStore = $page['id'];
                        $tokenToStore = $page['access_token']; // Page Access Token
                        $nameToStore = $page['name'];
                    }
                }
            }

            // For WhatsApp, the flow is often different (Embedded Signup), but if using simple OAuth:
            // We would fetch WABA IDs. Leaving basic user auth for now unless specific WABA endpoint is known.

            $socialAccount = SocialAccount::updateOrCreate(
                [
                    'platform' => $platform,
                    // Use the Page ID if found, otherwise User ID (fallback)
                    'external_account_id' => $idToStore,
                ],
                [
                    'name' => $nameToStore,
                    'credentials' => [
                        'access_token' => $tokenToStore, // Page Token is critical for automation
                        'refresh_token' => $user->refreshToken, // User refresh token might still be needed
                        'user_id' => $user->getId(), // Keep original user ID for reference
                        'expires_in' => $user->expiresIn,
                    ],
                    'is_active' => true,
                ]
            );

            return redirect()->route('filament.dashboard.pages.connections')
                ->with('status', ucfirst($platform).' ('.$nameToStore.') connected successfully!');

        } catch (\Exception $e) {
            Log::error("Social connection failed for {$platform}: ".$e->getMessage());

            return redirect()->route('filament.dashboard.pages.connections')
                ->with('error', 'Failed to connect '.ucfirst($platform).' account.');
        }
    }

    protected function getDriverForPlatform(string $platform): string
    {
        return match ($platform) {
            'whatsapp', 'instagram' => 'facebook',
            default => $platform,
        };
    }

    protected function getScopes(string $platform): array
    {
        return match ($platform) {
            'facebook', 'instagram' => [
                'pages_messaging',
                'pages_show_list',
                'pages_manage_metadata',
                'instagram_basic',
                'instagram_manage_messages',
            ],
            'whatsapp' => [
                'whatsapp_business_management',
                'whatsapp_business_messaging',
            ],
            'google' => [
                'https://www.googleapis.com/auth/businessmessages',
            ],
            default => [],
        };
    }
}
