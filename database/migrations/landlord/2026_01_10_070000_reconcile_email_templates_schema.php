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
        Schema::connection('landlord')->table('email_templates', function (Blueprint $table) {
            if (Schema::connection('landlord')->hasColumn('email_templates', 'slug')) {
                $table->renameColumn('slug', 'key');
            }
            if (Schema::connection('landlord')->hasColumn('email_templates', 'body')) {
                $table->renameColumn('body', 'body_html');
            }
            if (!Schema::connection('landlord')->hasColumn('email_templates', 'body_text')) {
                $table->text('body_text')->nullable()->after('body_html');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('email_templates', function (Blueprint $table) {
            if (Schema::connection('landlord')->hasColumn('email_templates', 'key')) {
                $table->renameColumn('key', 'slug');
            }
            if (Schema::connection('landlord')->hasColumn('email_templates', 'body_html')) {
                $table->renameColumn('body_html', 'body');
            }
            if (Schema::connection('landlord')->hasColumn('email_templates', 'body_text')) {
                $table->dropColumn('body_text');
            }
        });
    }
};
