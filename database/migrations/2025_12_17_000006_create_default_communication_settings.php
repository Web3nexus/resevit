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
        Schema::create('default_email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // smtp, sendgrid, mailgun, ses
            
            // SMTP Configuration
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable(); // Encrypted
            $table->string('smtp_encryption')->nullable(); // tls, ssl
            
            // API-based providers
            $table->text('api_key')->nullable(); // Encrypted
            $table->string('api_region')->nullable(); // For AWS SES
            
            // From details
            $table->string('from_email');
            $table->string('from_name');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('default_sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // twilio, vonage, aws_sns
            
            $table->text('api_key')->nullable(); // Encrypted (Twilio SID, Vonage Key)
            $table->text('api_secret')->nullable(); // Encrypted (Twilio Token, Vonage Secret)
            $table->string('from_number');
            $table->string('api_region')->nullable(); // For AWS SNS
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_email_settings');
        Schema::dropIfExists('default_sms_settings');
    }
};
