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
        // 1. Drop foreign keys from influencer_earnings if it still exists
        if (Schema::hasTable('influencer_earnings')) {
            Schema::table('influencer_earnings', function (Blueprint $table) {
                if (Schema::hasColumn('influencer_earnings', 'influencer_id')) {
                    // Try to drop foreign key - ignore if it fails (might not exist)
                    try {
                        $table->dropForeign(['influencer_id']);
                    } catch (\Exception $e) {
                    }
                }
            });

            // Rename to referral_earnings
            Schema::rename('influencer_earnings', 'referral_earnings');
        }

        // 2. Drop foreign key from withdrawal_requests if it exists
        if (Schema::hasTable('withdrawal_requests')) {
            Schema::table('withdrawal_requests', function (Blueprint $table) {
                if (Schema::hasColumn('withdrawal_requests', 'influencer_id')) {
                    try {
                        $table->dropForeign(['influencer_id']);
                    } catch (\Exception $e) {
                    }
                    $table->dropColumn('influencer_id');
                }

                // Add morphs if not present
                if (!Schema::hasColumn('withdrawal_requests', 'requester_id')) {
                    $table->nullableMorphs('requester');
                }
            });
        }

        // 3. Update referral_earnings table
        if (Schema::hasTable('referral_earnings')) {
            Schema::table('referral_earnings', function (Blueprint $table) {
                if (Schema::hasColumn('referral_earnings', 'influencer_id')) {
                    // Drop foreign key first - it might still have the old name
                    try {
                        $table->dropForeign('influencer_earnings_influencer_id_foreign');
                    } catch (\Exception $e) {
                        try {
                            $table->dropForeign(['influencer_id']);
                        } catch (\Exception $e2) {
                        }
                    }
                    $table->dropColumn('influencer_id');
                }

                if (!Schema::hasColumn('referral_earnings', 'earner_id')) {
                    $table->nullableMorphs('earner');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropMorphs('requester');
            $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
        });

        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->dropMorphs('earner');
            $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
        });
        Schema::rename('referral_earnings', 'influencer_earnings');
    }
};
