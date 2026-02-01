<?php

namespace App\Providers;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppSettingsServiceProvider extends ServiceProvider
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
        try {
            // Check if table exists to avoid migration errors
            if (! Schema::hasTable('platform_settings')) {
                return;
            }

            $settings = PlatformSetting::current();

            if (! $settings) {
                return;
            }

            // 1. Google Config
            if (! empty($settings->plugin_settings['google_client_id'])) {
                Config::set('services.google.client_id', $settings->plugin_settings['google_client_id']);
            }
            if (! empty($settings->plugin_settings['google_client_secret'])) {
                Config::set('services.google.client_secret', $settings->plugin_settings['google_client_secret']);
            }
            if (! empty($settings->plugin_settings['google_redirect_uri'])) {
                Config::set('services.google.redirect', $settings->plugin_settings['google_redirect_uri']);
            }

            // 2. Facebook/Meta Config
            if (! empty($settings->plugin_settings['facebook_app_id'])) {
                Config::set('services.facebook.client_id', $settings->plugin_settings['facebook_app_id']);
            }
            if (! empty($settings->plugin_settings['facebook_app_secret'])) {
                Config::set('services.facebook.client_secret', $settings->plugin_settings['facebook_app_secret']);
            }
            if (! empty($settings->plugin_settings['facebook_redirect_uri'])) {
                Config::set('services.facebook.redirect', $settings->plugin_settings['facebook_redirect_uri']);
            }

            // 3. Stripe Config
            if (! empty($settings->stripe_settings)) {
                $stripe = $settings->stripe_settings;
                $mode = $settings->stripe_mode ? 'live' : 'test';

                if (! empty($stripe[$mode]['publishable_key'])) {
                    Config::set('services.stripe.key', $stripe[$mode]['publishable_key']);
                }
                if (! empty($stripe[$mode]['secret_key'])) {
                    Config::set('services.stripe.secret', $stripe[$mode]['secret_key']);
                }
                // If you use cashier or other stripe packages, set them here too
                Config::set('cashier.key', Config::get('services.stripe.key'));
                Config::set('cashier.secret', Config::get('services.stripe.secret'));
            }

        } catch (\Exception $e) {
            // Fail silently during boot to not break artisan commands
        }
    }
}
