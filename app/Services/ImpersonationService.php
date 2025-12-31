<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ImpersonationService
{
    public function generateImpersonationUrl(User $user): ?string
    {
        $tenant = $user->tenants->first();

        if (!$tenant) {
            return null;
        }

        $domain = \App\Helpers\DomainHelper::getPlatformSubdomain($tenant->slug);

        if (!$domain) {
            return null;
        }

        $token = Str::random(64);

        Cache::put("impersonation_token_{$token}", [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'original_guard' => 'securegate', // Tracking where they came from
        ], now()->addMinutes(5));

        // Construct the URL manually to ensure protocol and domain are correct
        $scheme = request()->secure() ? 'https' : 'http';

        // Use the platform subdomain for the entry point to ensure session is established on the correct domain
        return "{$scheme}://{$domain}/impersonate/enter?token={$token}";
    }
}
