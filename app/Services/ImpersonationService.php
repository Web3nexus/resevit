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

        if (! $tenant) {
            return null;
        }

        // If the tenant model has domains relation loaded or available
        $domain = $tenant->domains->first()?->domain ?? $tenant->domain;

        if (! $domain) {
            return null;
        }

        $token = Str::random(64);

        Cache::put("impersonation_token_{$token}", [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'original_guard' => 'securegate', // Tracking where they came from
        ], now()->addMinutes(5));

        // Construct the URL manually to ensure protocol and domain are correct
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?? 'http';

        // If using standard Stancl Tenancy routing, we can try to use route() if domain routing is set up globally
        // But manual construction is safer for cross-domain calls
        return "{$scheme}://{$domain}/impersonate/enter?token={$token}";
    }
}
