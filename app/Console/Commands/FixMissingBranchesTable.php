<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FixMissingBranchesTable extends Command
{
    protected $signature = 'fix:missing-branches {tenant_id}';
    protected $description = 'Force create missing branches table for a specific tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant {$tenantId} not found.");
            return;
        }

        $this->info("Initializing tenancy for {$tenantId}...");
        tenancy()->initialize($tenant);

        if (Schema::hasTable('branches')) {
            $this->info("Table 'branches' already exists.");
        } else {
            $this->info("Creating 'branches' table...");
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->json('opening_hours')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
            $this->info("Table 'branches' created successfully!");
        }

        // Also create a default branch if none exists
        if (\Illuminate\Support\Facades\DB::table('branches')->count() === 0) {
            \Illuminate\Support\Facades\DB::table('branches')->insert([
                'name' => 'Main Branch',
                'slug' => 'main-branch',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info("Default 'Main Branch' created.");
        }

        tenancy()->end();
    }
}
