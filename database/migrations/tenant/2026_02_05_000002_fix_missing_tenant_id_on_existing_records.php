<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fill existing tenant_id if null
        $tenantId = tenant('id');

        if ($tenantId) {
            DB::table('branches')->whereNull('tenant_id')->update(['tenant_id' => $tenantId]);
            DB::table('staff')->whereNull('tenant_id')->update(['tenant_id' => $tenantId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert specifically
    }
};
