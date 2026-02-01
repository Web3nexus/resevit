<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendOrderConfirmation(Order $order)
    {
        $this->sendEmail($order, 'order_confirmation');
        $this->sendSMS($order, 'order_confirmation');
    }

    public function sendOrderStatusUpdate(Order $order)
    {
        $this->sendEmail($order, 'order_status_update');
        $this->sendSMS($order, 'order_status_update');
    }

    protected function sendEmail(Order $order, string $templateKey)
    {
        try {
            $template = DB::table('email_templates')->where('key', $templateKey)->first();
            if (! $template || ! $template->is_active) {
                return;
            }

            $settings = DB::table('email_settings')->first();
            if (! $settings) {
                return;
            } // Or use defaults

            $content = $this->replaceVariables($template->body_html, $order);
            $subject = $this->replaceVariables($template->subject, $order);

            // Here we would dynamically configure the mailer based on $settings
            // For now, we'll log it or use default mailer

            // Mail::to($order->customer_email)->send(...);

            Log::info("Sending Email [$subject] to {$order->customer_email}");

        } catch (\Exception $e) {
            Log::error('Failed to send email: '.$e->getMessage());
        }
    }

    protected function sendSMS(Order $order, string $templateKey)
    {
        try {
            $template = DB::table('sms_templates')->where('key', $templateKey)->first();
            if (! $template || ! $template->is_active) {
                return;
            }

            $settings = DB::table('sms_settings')->first();
            if (! $settings) {
                return;
            }

            $content = $this->replaceVariables($template->content, $order);

            // Here we would use Twilio/Vonage API based on $settings
            // Twilio::message($order->customer_phone, $content);

            Log::info("Sending SMS to {$order->customer_phone}: $content");

        } catch (\Exception $e) {
            Log::error('Failed to send SMS: '.$e->getMessage());
        }
    }

    protected function replaceVariables($content, Order $order)
    {
        $variables = [
            '{customer_name}' => $order->customer_name,
            '{order_number}' => $order->order_number,
            '{order_total}' => '$'.number_format($order->total, 2),
            '{order_url}' => route('tenant.order.status', $order->id), // Use ID not object for safety
            '{status}' => ucfirst($order->status),
        ];

        return str_replace(array_keys($variables), array_values($variables), $content);
    }
}
