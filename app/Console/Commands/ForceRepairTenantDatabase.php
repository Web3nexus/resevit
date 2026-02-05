<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ForceRepairTenantDatabase extends Command
{
    protected $signature = 'tenant:force-repair {tenant_id}';
    protected $description = 'Force re-run all migrations for a broken tenant database';

    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant {$tenantId} not found.");
            return 1;
        }

        $this->info("🔧 Starting complete database repair for tenant: {$tenantId}");

        // Initialize tenancy
        tenancy()->initialize($tenant);

        // Step 1: Clear migrations table to force re-run
        $this->info("📋 Clearing migrations history...");
        DB::table('migrations')->truncate();

        // Step 2: Run all tenant migrations fresh
        $this->info("🚀 Running all tenant migrations...");
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--force' => true,
            '--realpath' => true,
        ]);

        $this->info(Artisan::output());

        tenancy()->end();

        $this->info("✅ Tenant database repair completed successfully!");
        $this->info("🎉 All tables should now be created.");

        return 0;
    }
}
