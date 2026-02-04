<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection('landlord')->hasColumn('platform_settings', 'stripe_mode')) {
            Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
                $table->enum('stripe_mode', ['test', 'live'])->default('test')->after('stripe_settings');
            });
        }

        // Migrate existing stripe_settings to new structure
        $this->migrateExistingStripeSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('stripe_mode');
        });
    }

    /**
     * Migrate existing stripe settings to new structure with test/live separation
     */
    protected function migrateExistingStripeSettings(): void
    {
        $settings = DB::connection('landlord')
            ->table('platform_settings')
            ->first();

        if ($settings && $settings->stripe_settings) {
            $stripeSettings = json_decode($settings->stripe_settings, true);

            // If old structure exists, migrate to new structure
            if (isset($stripeSettings['publishable_key']) || isset($stripeSettings['secret_key'])) {
                $newStructure = [
                    'mode' => 'test',
                    'test' => [
                        'publishable_key' => $stripeSettings['publishable_key'] ?? '',
                        'secret_key' => $stripeSettings['secret_key'] ?? '',
                        'webhook_secret' => $stripeSettings['webhook_secret'] ?? '',
                    ],
                    'live' => [
                        'publishable_key' => '',
                        'secret_key' => '',
                        'webhook_secret' => '',
                    ],
                ];

                DB::connection('landlord')
                    ->table('platform_settings')
                    ->where('id', $settings->id)
                    ->update([
                        'stripe_settings' => json_encode($newStructure),
                        'stripe_mode' => 'test',
                    ]);
            }
        }
    }
};
