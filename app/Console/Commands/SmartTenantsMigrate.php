<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Migrations\Migrator;
use Stancl\Tenancy\Commands\Migrate;
use Stancl\Tenancy\Events\DatabaseMigrated;
use Stancl\Tenancy\Events\MigratingDatabase;
use Stancl\Tenancy\Exceptions\TenantDatabaseDoesNotExistException;

class SmartTenantsMigrate extends Migrate
{
    protected $signature = 'tenants:smart-migrate {--tenants=* : The tenant(s) to migrate} {--path=* : The path(s) to the migrations files to be executed} {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths} {--schema-path= : The path to a schema dump file} {--pretend : Dump the SQL queries that would be run} {--step : Force the migrations to be run so they can be rolled back individually} {--force : Force the operation to run when in production} {--graceful : Return a successful exit code even if an error occurs}';

    protected $description = 'Run migrations for tenant(s), skipping broken ones.';

    public function handle()
    {
        foreach (config('tenancy.migration_parameters') as $parameter => $value) {
            if (!$this->input->hasParameterOption($parameter)) {
                $this->input->setOption(ltrim($parameter, '-'), $value);
            }
        }

        if (!$this->confirmToProceed()) {
            return;
        }

        $tenants = $this->getTenants();

        foreach ($tenants as $tenant) {
            $this->line("Tenant: {$tenant->getTenantKey()}");

            try {
                tenancy()->initialize($tenant);

                event(new MigratingDatabase($tenant));

                // Migrate
                parent::handle();

                event(new DatabaseMigrated($tenant));

                $this->info("Migrated: {$tenant->getTenantKey()}");

            } catch (TenantDatabaseDoesNotExistException $e) {
                $this->error("Skipped Tenant {$tenant->getTenantKey()}: Database does not exist.");
            } catch (\Exception $e) {
                $this->error("Skipped Tenant {$tenant->getTenantKey()}: " . $e->getMessage());
            }
        }
    }
}
