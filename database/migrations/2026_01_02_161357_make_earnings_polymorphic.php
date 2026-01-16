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
            $this->dropForeignKeyIfExists('influencer_earnings', 'influencer_id');

            // Rename to referral_earnings if target doesn't exist
            if (!Schema::hasTable('referral_earnings')) {
                Schema::rename('influencer_earnings', 'referral_earnings');
            }
        }

        // 2. Drop foreign key from withdrawal_requests if it exists
        if (Schema::hasTable('withdrawal_requests')) {
            if (Schema::hasColumn('withdrawal_requests', 'influencer_id')) {
                $this->dropForeignKeyIfExists('withdrawal_requests', 'influencer_id');
                Schema::table('withdrawal_requests', function (Blueprint $table) {
                    $table->dropColumn('influencer_id');
                });
            }

            // Add morphs if not present
            if (!Schema::hasColumn('withdrawal_requests', 'requester_id')) {
                Schema::table('withdrawal_requests', function (Blueprint $table) {
                    $table->nullableMorphs('requester');
                });
            }
        }

        // 3. Update referral_earnings table
        if (Schema::hasTable('referral_earnings')) {
            if (Schema::hasColumn('referral_earnings', 'influencer_id')) {
                $this->dropForeignKeyIfExists('referral_earnings', 'influencer_id');
                Schema::table('referral_earnings', function (Blueprint $table) {
                    $table->dropColumn('influencer_id');
                });
            }

            if (!Schema::hasColumn('referral_earnings', 'earner_id')) {
                Schema::table('referral_earnings', function (Blueprint $table) {
                    $table->nullableMorphs('earner');
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
        if (Schema::hasTable('withdrawal_requests')) {
            Schema::table('withdrawal_requests', function (Blueprint $table) {
                if (Schema::hasColumn('withdrawal_requests', 'requester_id')) {
                    $table->dropMorphs('requester');
                }
                if (!Schema::hasColumn('withdrawal_requests', 'influencer_id')) {
                    $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('referral_earnings')) {
            Schema::table('referral_earnings', function (Blueprint $table) {
                if (Schema::hasColumn('referral_earnings', 'earner_id')) {
                    $table->dropMorphs('earner');
                }
                if (!Schema::hasColumn('referral_earnings', 'influencer_id')) {
                    $table->foreignId('influencer_id')->nullable()->constrained('influencers')->onDelete('cascade');
                }
            });

            if (!Schema::hasTable('influencer_earnings')) {
                Schema::rename('referral_earnings', 'influencer_earnings');
            }
        }
    }
};
