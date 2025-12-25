<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {id} {--domain=}';
    protected $description = 'Create a new tenant';

    public function handle()
    {
        $tenantId = $this->argument('id');
        $domain = $this->option('domain');

        $tenant = Tenant::create([
            'id' => $tenantId,
            'slug' => $tenantId,
        ]);

        if ($domain) {
            $tenant->domains()->create(['domain' => $domain]);
            $this->info("Tenant '{$tenantId}' created with domain '{$domain}'");
        } else {
            $this->info("Tenant '{$tenantId}' created");
        }

        return 0;
    }
}
