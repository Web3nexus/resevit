<?php
try {
    echo "Starting Tenant Creation...\n";
    $user = \App\Models\LandlordUser::firstOrCreate(
        ['email' => 'vincent@resevit.test'],
        ['name' => 'Vincent', 'password' => bcrypt('password'), 'terms_accepted' => true]
    );
    echo "User found/created: " . $user->id . "\n";

    if (!\App\Models\Tenant::where('id', 'demo')->exists()) {
        echo "Creating Tenant demo...\n";
        $tenant = app(\App\Services\TenantCreatorService::class)->createTenant(
            $user,
            'Demo Restaurant',
            'demo',
            ['staff_count' => '1-5']
        );
        echo "Tenant created: " . $tenant->id . "\n";
    } else {
        echo "Tenant demo already exists.\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
