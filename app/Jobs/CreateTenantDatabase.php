<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateTenantDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Tenant $tenant, protected string $password)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenant = $this->tenant;
        $owner = $tenant->owner;

        $databaseName = $tenant->database_name;

        DB::connection('landlord')->statement("CREATE DATABASE {$databaseName}");

        // Initialize tenant storage directories
        $tenantStoragePath = storage_path("tenant{$tenant->id}");
        $directories = [
            $tenantStoragePath.'/framework/cache',
            $tenantStoragePath.'/framework/sessions',
            $tenantStoragePath.'/framework/views',
            $tenantStoragePath.'/app/public',
            $tenantStoragePath.'/app/private',
        ];

        foreach ($directories as $directory) {
            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
        }

        $tenant->configure()->use();

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => 'TenantRolesSeeder',
            '--force' => true,
        ]);

        $user = User::create([
            'name' => $owner->name,
            'email' => $owner->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('business_owner');
    }
}
