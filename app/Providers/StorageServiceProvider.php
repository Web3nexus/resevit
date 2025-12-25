<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Skip during migrations or other console commands that might interfere
        if (app()->runningInConsole()) {
            $command = implode(' ', $_SERVER['argv'] ?? []);
            if (str_contains($command, 'migrate') || str_contains($command, 'package:discover')) {
                return;
            }
        }

        try {
            // Check if table exists before querying
            if (!\Illuminate\Support\Facades\Schema::connection('landlord')->hasTable('storage_settings')) {
                return;
            }

            $setting = \App\Models\StorageSetting::where('is_active', true)->latest()->first();

            if (!$setting) {
                return;
            }

            if ($setting->active_disk !== 'public') {
                // Configure S3 driver (used for both S3 and R2)
                config([
                    'filesystems.disks.s3.key' => $setting->s3_key,
                    'filesystems.disks.s3.secret' => $setting->s3_secret,
                    'filesystems.disks.s3.region' => $setting->s3_region ?: 'auto',
                    'filesystems.disks.s3.bucket' => $setting->s3_bucket,
                    'filesystems.disks.s3.url' => $setting->cdn_url ?: config('filesystems.disks.s3.url'),
                    'filesystems.disks.s3.endpoint' => $setting->s3_endpoint,
                    // R2 usually doesn't want path style base URLs
                    'filesystems.disks.s3.use_path_style_endpoint' => !str_contains($setting->s3_endpoint ?? '', 'r2.cloudflarestorage.com'),
                ]);

                // Switch Media Library to use S3 disk
                config(['media-library.disk_name' => 's3']);

                // If using S3, we might also want to set it as default disk for general storage
                config(['filesystems.default' => 's3']);
            }

            // Apply CDN URL to the relevant disk if provided
            if ($setting->cdn_url) {
                if ($setting->active_disk === 'public') {
                    config(['filesystems.disks.public.url' => rtrim($setting->cdn_url, '/') . '/storage']);
                } else {
                    config(['filesystems.disks.s3.url' => rtrim($setting->cdn_url, '/')]);
                }
            }

        } catch (\Exception $e) {
            // Silently fail to avoid crashing the app if DB is not ready
        }
    }
}
