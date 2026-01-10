<?php

namespace App\Services;

use App\Models\DefaultEmailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailConfigService
{
    /**
     * Configure Laravel's mail driver dynamically from database settings
     */
    public static function configureMail(): void
    {
        $settings = DefaultEmailSetting::where('is_active', true)->first();

        if (!$settings) {
            // Fallback to .env configuration if no active settings found
            return;
        }

        // Configure mail driver based on provider
        switch ($settings->provider) {
            case 'smtp':
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp', [
                    'transport' => 'smtp',
                    'host' => $settings->smtp_host,
                    'port' => $settings->smtp_port,
                    'encryption' => $settings->smtp_encryption,
                    'username' => $settings->smtp_username,
                    'password' => $settings->smtp_password,
                    'timeout' => null,
                ]);
                break;

            case 'ses':
                Config::set('mail.default', 'ses');
                Config::set('services.ses', [
                    'key' => $settings->api_key,
                    'secret' => $settings->smtp_password,
                    'region' => $settings->api_region ?? 'us-east-1',
                ]);
                break;

            case 'mailgun':
                Config::set('mail.default', 'mailgun');
                Config::set('services.mailgun', [
                    'domain' => $settings->smtp_host,
                    'secret' => $settings->api_key,
                    'endpoint' => 'api.mailgun.net',
                ]);
                break;

            case 'postmark':
                Config::set('mail.default', 'postmark');
                Config::set('services.postmark', [
                    'token' => $settings->api_key,
                ]);
                break;
        }

        // Set from address
        Config::set('mail.from', [
            'address' => $settings->from_email,
            'name' => $settings->from_name,
        ]);

        // Purge the mail manager to force reconfiguration
        Mail::purge();
    }

    /**
     * Get the active email configuration
     */
    public static function getActiveConfig(): ?DefaultEmailSetting
    {
        return DefaultEmailSetting::where('is_active', true)->first();
    }

    /**
     * Test the email configuration by sending a test email
     */
    public static function testConfiguration(string $toEmail): bool
    {
        try {
            self::configureMail();

            Mail::raw('This is a test email from Resevit. If you received this, your email configuration is working correctly!', function ($message) use ($toEmail) {
                $message->to($toEmail)
                    ->subject('Test Email from Resevit');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Email test failed: ' . $e->getMessage());
            return false;
        }
    }
}
