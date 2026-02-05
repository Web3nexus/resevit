<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class FixMissingBranchesTable extends Command
{
    protected $signature = 'fix:missing-tables {tenant_id}';
    protected $description = 'Force create all missing tables for a specific tenant';

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

        // Create branches table
        $this->createBranchesTable();

        // Create permission tables
        $this->createPermissionTables();

        // Create staff table
        $this->createStaffTable();

        // Create staff work logs
        $this->createStaffWorkLogsTable();

        $this->info("All missing tables created successfully!");
        tenancy()->end();
    }

    protected function createBranchesTable()
    {
        if (Schema::hasTable('branches')) {
            $this->info("✓ Table 'branches' already exists.");
            return;
        }

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

        // Create default branch
        if (DB::table('branches')->count() === 0) {
            DB::table('branches')->insert([
                'name' => 'Main Branch',
                'slug' => 'main-branch',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info("✓ Default 'Main Branch' created.");
        }
    }

    protected function createPermissionTables()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        // Create permissions table
        if (!Schema::hasTable($tableNames['permissions'])) {
            $this->info("Creating '{$tableNames['permissions']}' table...");
            Schema::create($tableNames['permissions'], function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 125);
                $table->string('guard_name', 125);
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });
            $this->info("✓ Permissions table created.");
        }

        // Create roles table
        if (!Schema::hasTable($tableNames['roles'])) {
            $this->info("Creating '{$tableNames['roles']}' table...");
            Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
                $table->bigIncrements('id');
                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key']);
                }
                $table->string('name', 125);
                $table->string('guard_name', 125);
                $table->timestamps();
                if ($teams) {
                    $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
                } else {
                    $table->unique(['name', 'guard_name']);
                }
            });
            $this->info("✓ Roles table created.");
        }

        // Create model_has_permissions
        if (!Schema::hasTable($tableNames['model_has_permissions'])) {
            $this->info("Creating '{$tableNames['model_has_permissions']}' table...");
            Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type']);
                $table->foreign('permission_id')->references('id')->on($tableNames['permissions'])->onDelete('cascade');
                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key']);
                    $table->primary([$columnNames['team_foreign_key'], 'permission_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_primary');
                } else {
                    $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_primary');
                }
            });
            $this->info("✓ Model has permissions table created.");
        }

        // Create model_has_roles
        if (!Schema::hasTable($tableNames['model_has_roles'])) {
            $this->info("Creating '{$tableNames['model_has_roles']}' table...");
            Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type']);
                $table->foreign('role_id')->references('id')->on($tableNames['roles'])->onDelete('cascade');
                if ($teams) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key']);
                    $table->primary([$columnNames['team_foreign_key'], 'role_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_primary');
                } else {
                    $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_primary');
                }
            });
            $this->info("✓ Model has roles table created.");
        }

        // Create role_has_permissions
        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            $this->info("Creating '{$tableNames['role_has_permissions']}' table...");
            Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
                $table->foreign('permission_id')->references('id')->on($tableNames['permissions'])->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on($tableNames['roles'])->onDelete('cascade');
                $table->primary(['permission_id', 'role_id']);
            });
            $this->info("✓ Role has permissions table created.");
        }
    }

    protected function createStaffTable()
    {
        if (Schema::hasTable('staff')) {
            $this->info("✓ Table 'staff' already exists.");
            return;
        }

        $this->info("Creating 'staff' table...");
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // No FK constraint - users table is in landlord DB
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('position');
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->date('hire_date');
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'on_leave', 'suspended'])->default('active');
            $table->json('availability')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('swift_bic')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('branch_id');
        });
        $this->info("✓ Staff table created.");
    }

    protected function createStaffWorkLogsTable()
    {
        if (Schema::hasTable('staff_work_logs')) {
            $this->info("✓ Table 'staff_work_logs' already exists.");
            return;
        }

        $this->info("Creating 'staff_work_logs' table...");
        Schema::create('staff_work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->timestamp('check_in');
            $table->timestamp('check_out')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->timestamps();
        });
        $this->info("✓ Staff work logs table created.");
    }
}
