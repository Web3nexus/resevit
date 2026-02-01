<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('email'); // email, sms
            $table->string('key');
            $table->string('name');
            $table->string('subject')->nullable(); // Only for email
            $table->longText('content'); // HTML for email, text for SMS
            $table->longText('plain_content')->nullable(); // Plain text for email
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'key']);
        });

        // Seed default templates
        $templates = [
            [
                'type' => 'email',
                'key' => 'order_confirmation',
                'name' => 'Order Confirmation',
                'subject' => 'Order Confirmation #{order_number}',
                'content' => '<h1>Thanks for your order!</h1><p>Hi {customer_name},</p><p>We received your order #{order_number} for {order_total}.</p><p><a href="{order_url}">Track Order</a></p>',
                'plain_content' => 'Thanks for your order #{order_number} for {order_total}. Track here: {order_url}',
                'variables' => json_encode(['customer_name', 'order_number', 'order_total', 'order_url']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'sms',
                'key' => 'order_confirmation',
                'name' => 'Order Confirmation',
                'subject' => null,
                'content' => 'Thanks {customer_name}! Your order #{order_number} has been received. Total: {order_total}. View status: {order_url}',
                'plain_content' => null,
                'variables' => json_encode(['customer_name', 'order_number', 'order_total', 'order_url']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('notification_templates')->insert($templates);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
