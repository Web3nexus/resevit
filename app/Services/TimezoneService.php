<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TimezoneService
{
    /**
     * Get the current tenant's timezone.
     */
    public function getCurrentTenantTimezone(): string
    {
        if (!tenancy()->initialized) {
            return config('app.timezone', 'UTC');
        }

        return tenancy()->tenant->timezone ?? config('app.timezone', 'UTC');
    }

    /**
     * Convert UTC time to tenant's timezone.
     */
    public function convertToTenantTimezone(Carbon $utcTime): Carbon
    {
        $timezone = $this->getCurrentTenantTimezone();
        return $utcTime->copy()->setTimezone($timezone);
    }

    /**
     * Convert local time to UTC.
     */
    public function convertToUTC(Carbon $localTime, ?string $timezone = null): Carbon
    {
        $tz = $timezone ?? $this->getCurrentTenantTimezone();

        // If the time doesn't have a timezone set, assume it's in the tenant's timezone
        if ($localTime->timezone->getName() === 'UTC') {
            $localTime = $localTime->copy()->setTimezone($tz);
        }

        return $localTime->copy()->setTimezone('UTC');
    }

    /**
     * Format time in tenant's timezone.
     */
    public function formatTimeInTenantTimezone(Carbon $time, string $format = 'Y-m-d H:i:s'): string
    {
        return $this->convertToTenantTimezone($time)->format($format);
    }

    /**
     * Get list of available timezones for selection.
     */
    public function getAvailableTimezones(): array
    {
        return Cache::remember('timezones_list', 86400, function () {
            $timezones = [];

            foreach (timezone_identifiers_list() as $timezone) {
                // Group by region for better UX
                $parts = explode('/', $timezone);
                $region = $parts[0];
                $city = $parts[1] ?? $timezone;

                // Skip deprecated and special timezones
                if (in_array($region, ['Etc', 'SystemV'])) {
                    continue;
                }

                $timezones[$timezone] = str_replace('_', ' ', $city) . ' (' . $timezone . ')';
            }

            asort($timezones);
            return $timezones;
        });
    }

    /**
     * Get common timezones for quick selection.
     */
    public function getCommonTimezones(): array
    {
        return [
            'America/New_York' => 'Eastern Time (US & Canada)',
            'America/Chicago' => 'Central Time (US & Canada)',
            'America/Denver' => 'Mountain Time (US & Canada)',
            'America/Los_Angeles' => 'Pacific Time (US & Canada)',
            'America/Phoenix' => 'Arizona',
            'America/Anchorage' => 'Alaska',
            'Pacific/Honolulu' => 'Hawaii',
            'Europe/London' => 'London',
            'Europe/Paris' => 'Paris',
            'Europe/Berlin' => 'Berlin',
            'Asia/Tokyo' => 'Tokyo',
            'Asia/Shanghai' => 'Shanghai',
            'Asia/Dubai' => 'Dubai',
            'Australia/Sydney' => 'Sydney',
            'UTC' => 'UTC',
        ];
    }

    /**
     * Validate timezone string.
     */
    public function isValidTimezone(string $timezone): bool
    {
        return in_array($timezone, timezone_identifiers_list());
    }

    /**
     * Get timezone offset string (e.g., "UTC-8" or "UTC+5:30").
     */
    public function getTimezoneOffset(?string $timezone = null): string
    {
        $tz = $timezone ?? $this->getCurrentTenantTimezone();
        $offset = Carbon::now($tz)->format('P');
        return "UTC{$offset}";
    }
}
