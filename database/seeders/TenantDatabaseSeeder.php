<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ensure at least one branch exists
        $branch = Branch::firstOrCreate(
            ['slug' => 'main-branch'],
            [
                'name' => 'Main Branch',
                'is_active' => true,
            ]
        );

        // 2. Set the current team ID for Spatie
        setPermissionsTeamId($branch->id);

        // 3. Run Seeders
        $this->call(TenantPermissionsSeeder::class);
        $this->call(TenantRolesSeeder::class);
        $this->call(TenantUserSeeder::class);
        $this->call(StaffDemoSeeder::class);
    }
}
