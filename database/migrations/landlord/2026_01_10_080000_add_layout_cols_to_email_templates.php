<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'landlord';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->table('email_templates', function (Blueprint $table) {
            $table->boolean('use_layout')->default(true)->after('is_active');
            $table->string('email_title')->nullable()->after('use_layout');
            $table->string('email_badge')->nullable()->after('email_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['use_layout', 'email_title', 'email_badge']);
        });
    }
};
