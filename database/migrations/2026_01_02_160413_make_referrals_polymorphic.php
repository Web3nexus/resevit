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
        if (Schema::hasTable('referral_link_clicks')) {
            if (Schema::hasColumn('referral_link_clicks', 'influencer_id')) {
                $this->dropForeignKeyIfExists('referral_link_clicks', 'influencer_id');
                Schema::table('referral_link_clicks', function (Blueprint $table) {
                    $table->dropColumn('influencer_id');
                });
            }
            if (!Schema::hasColumn('referral_link_clicks', 'referrer_id')) {
                Schema::table('referral_link_clicks', function (Blueprint $table) {
                    $table->nullableMorphs('referrer');
                });
            }
        }

        // Update referrals table
        if (Schema::hasTable('referrals')) {
            if (Schema::hasColumn('referrals', 'influencer_id')) {
                $this->dropForeignKeyIfExists('referrals', 'influencer_id');
                Schema::table('referrals', function (Blueprint $table) {
                    $table->dropColumn('influencer_id');
                });
            }
            if (!Schema::hasColumn('referrals', 'referrer_id')) {
                Schema::table('referrals', function (Blueprint $table) {
                    $table->nullableMorphs('referrer');
                });
            }
        }
    }

    /**
     * Drop a foreign key if it exists by checking information_schema.
     */
    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        $database = config('database.connections.' . (Schema::getConnection()->getName() ?: config('database.default')) . '.database');

        $foreignKey = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$database, $table, $column]))->first();

        if ($foreignKey) {
            Schema::table($table, function (Blueprint $tableObj) use ($foreignKey) {
                $tableObj->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('referrals')) {
            Schema::table('referrals', function (Blueprint $table) {
                if (Schema::hasColumn('referrals', 'referrer_id')) {
                    $table->dropMorphs('referrer');
                }
                if (!Schema::hasColumn('referrals', 'influencer_id')) {
                    $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('referral_link_clicks')) {
            Schema::table('referral_link_clicks', function (Blueprint $table) {
                if (Schema::hasColumn('referral_link_clicks', 'referrer_id')) {
                    $table->dropMorphs('referrer');
                }
                if (!Schema::hasColumn('referral_link_clicks', 'influencer_id')) {
                    $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
                }
            });
        }
    }
};
