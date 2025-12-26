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
        Schema::table('tenants', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('plan_id');
            $table->boolean('is_sponsored')->default(false)->after('is_public');
            $table->integer('sponsored_ranking')->default(0)->after('is_sponsored');
            $table->foreignId('business_category_id')->nullable()->constrained('business_categories')->nullOnDelete()->after('sponsored_ranking');
            $table->text('description')->nullable()->after('business_category_id');
            $table->string('cover_image')->nullable()->after('description');
            $table->string('seo_title')->nullable()->after('cover_image');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['business_category_id']);
            $table->dropColumn([
                'is_public',
                'is_sponsored',
                'sponsored_ranking',
                'business_category_id',
                'description',
                'cover_image',
                'seo_title',
                'seo_description',
            ]);
        });
    }
};
