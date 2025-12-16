<?php
try {
    echo "Running Fix Script...\n";
    $tenant = \App\Models\Tenant::first();
    if (!$tenant) {
        // Create user if missing
        $user = \App\Models\LandlordUser::firstOrCreate(
            ['email' => 'admin@resevit.test'],
            ['name' => 'Admin', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'terms_accepted' => true]
        ); // password is 'password'

        // Create tenant
        $tenant = app(\App\Services\TenantCreatorService::class)->createTenant(
            $user,
            'Resevit Main',
            'resevit',
            ['staff_count' => '1-5']
        );
        echo "Created new tenant: " . $tenant->id . "\n";
    } else {
        echo "Found existing tenant: " . $tenant->id . "\n";
    }

    if ($tenant) {
        \Stancl\Tenancy\Database\Models\Domain::updateOrCreate(
            ['tenant_id' => $tenant->id],
            ['domain' => 'resevit.test']
        );
        echo "SUCCESS: Linked 'resevit.test' to linked tenant.\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
