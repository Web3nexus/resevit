<?php

namespace App\Services\Communication;

use App\Models\EmailSetting;
use App\Models\DefaultEmailSetting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send an email using the configured provider
     */
    public function send(string $to, string $templateKey, array $data = []): bool
    {
        try {
            // Get the email template
            $template = EmailTemplate::where('key', $templateKey)
                ->where('is_active', true)
                ->first();

            if (!$template) {
                Log::error("Email template not found: {$templateKey}");
                return false;
            }

            // Render the template with data
            $rendered = $template->render($data);

            // Get email configuration
            $config = $this->getEmailConfig();

            if (!$config) {
                Log::error('No email configuration available');
                return false;
            }

            // Configure mail driver
            $this->configureMailDriver($config);

            // Send the email
            Mail::send([], [], function ($message) use ($to, $rendered, $config) {
                $message->to($to)
                    ->from($config['from_email'], $config['from_name'])
                    ->subject($rendered['subject'])
                    ->html($rendered['body_html']);

                if ($rendered['body_text']) {
                    $message->text($rendered['body_text']);
                }
            });

            return true;

        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get email configuration (tenant or default)
     */
    protected function getEmailConfig(): ?array
    {
        // Try to get tenant-specific settings
        $tenantSettings = EmailSetting::where('is_active', true)->first();

        if ($tenantSettings && !$tenantSettings->use_default) {
            return [
                'provider' => $tenantSettings->provider,
                'smtp_host' => $tenantSettings->smtp_host,
                'smtp_port' => $tenantSettings->smtp_port,
                'smtp_username' => $tenantSettings->smtp_username,
                'smtp_password' => $tenantSettings->smtp_password,
                'smtp_encryption' => $tenantSettings->smtp_encryption,
                'api_key' => $tenantSettings->api_key,
                'api_region' => $tenantSettings->api_region,
                'from_email' => $tenantSettings->from_email,
                'from_name' => $tenantSettings->from_name,
            ];
        }

        // Fall back to default settings
        $defaultSettings = DefaultEmailSetting::where('is_active', true)->first();

        if (!$defaultSettings) {
            return null;
        }

        return [
            'provider' => $defaultSettings->provider,
            'smtp_host' => $defaultSettings->smtp_host,
            'smtp_port' => $defaultSettings->smtp_port,
            'smtp_username' => $defaultSettings->smtp_username,
            'smtp_password' => $defaultSettings->smtp_password,
            'smtp_encryption' => $defaultSettings->smtp_encryption,
            'api_key' => $defaultSettings->api_key,
            'api_region' => $defaultSettings->api_region,
            'from_email' => $defaultSettings->from_email,
            'from_name' => $defaultSettings->from_name,
        ];
    }

    /**
     * Configure Laravel mail driver based on provider
     */
    protected function configureMailDriver(array $config): void
    {
        switch ($config['provider']) {
            case 'smtp':
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp' => [
                        'transport' => 'smtp',
                        'host' => $config['smtp_host'],
                        'port' => $config['smtp_port'],
                        'encryption' => $config['smtp_encryption'],
                        'username' => $config['smtp_username'],
                        'password' => $config['smtp_password'],
                    ],
                ]);
                break;

            case 'sendgrid':
                config([
                    'mail.default' => 'sendgrid',
                    'mail.mailers.sendgrid' => [
                        'transport' => 'sendgrid',
                    ],
                    'services.sendgrid.api_key' => $config['api_key'],
                ]);
                break;

            case 'mailgun':
                config([
                    'mail.default' => 'mailgun',
                    'services.mailgun.secret' => $config['api_key'],
                ]);
                break;

            case 'ses':
                config([
                    'mail.default' => 'ses',
                    'services.ses.key' => $config['api_key'],
                    'services.ses.region' => $config['api_region'] ?? 'us-east-1',
                ]);
                break;
        }
    }
}
