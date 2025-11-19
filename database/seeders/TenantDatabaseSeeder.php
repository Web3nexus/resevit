<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TenantRolesSeeder::class);
        $this->call(TenantUserSeeder::class);
    }
}
