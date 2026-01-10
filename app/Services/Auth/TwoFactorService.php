<?php

namespace App\Services\Auth;

use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TwoFactorService
{
    /**
     * Generate and send a 2FA code to the user
     */
    public function generateAndSendCode($user): void
    {
        $code = (string) rand(100000, 999999);
        $key = $this->getCacheKey($user);

        Cache::put($key, $code, now()->addMinutes(10));

        $user->notify(new TwoFactorCodeNotification($code));
    }

    /**
     * Verify the provided 2FA code
     */
    public function verifyCode($user, string $code): bool
    {
        $key = $this->getCacheKey($user);
        $cachedCode = Cache::get($key);

        if ($cachedCode && $cachedCode === $code) {
            Cache::forget($key);
            return true;
        }

        return false;
    }

    /**
     * Get the cache key for the user's 2FA code
     */
    protected function getCacheKey($user): string
    {
        return '2fa_code_' . get_class($user) . '_' . $user->id;
    }
}
