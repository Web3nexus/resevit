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
        // Update referral_link_clicks table
        Schema::table('referral_link_clicks', function (Blueprint $table) {
            if (Schema::hasColumn('referral_link_clicks', 'influencer_id')) {
                $table->dropForeign(['influencer_id']);
                $table->dropColumn('influencer_id');
            }
            $table->nullableMorphs('referrer');
        });

        // Update referrals table
        Schema::table('referrals', function (Blueprint $table) {
            if (Schema::hasColumn('referrals', 'influencer_id')) {
                $table->dropForeign(['influencer_id']);
                $table->dropColumn('influencer_id');
            }
            $table->nullableMorphs('referrer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropMorphs('referrer');
            $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
        });

        Schema::table('referral_link_clicks', function (Blueprint $table) {
            $table->dropMorphs('referrer');
            $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
        });
    }
};
