<?php
try {
    $domain = \Stancl\Tenancy\Database\Models\Domain::where('domain', 'resevit.test')->first();
    if (!$domain) {
        echo "No tenant found for resevit.test\n";
        exit;
    }

    $tenant = $domain->tenant;
    echo "Tenant ID: " . $tenant->id . "\n";
    echo "Tenant DB: " . $tenant->database_name . "\n";

    tenancy()->initialize($tenant);

    $userCount = \App\Models\User::count();
    echo "User Count in Tenant: " . $userCount . "\n";

    if ($userCount > 0) {
        $user = \App\Models\User::first();
        echo "First User: " . $user->email . "\n";
        // Check if password matches 'password' (bcrypt)
        // We can't easily check hash equality without knowing the plain text, but we can reset it.
    } else {
        echo "NO USERS in this tenant!\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
