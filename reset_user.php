<?php
try {
    $domain = \Stancl\Tenancy\Database\Models\Domain::where('domain', 'resevit.test')->first();
    if (!$domain) {
        echo "No tenant found for resevit.test\n";
        exit;
    }

    $tenant = $domain->tenant;
    tenancy()->initialize($tenant);

    $email = 'admin@resevit.test';
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        echo "User $email not found. Creating...\n";
        $user = \App\Models\User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => bcrypt('password'),
            'terms_accepted' => true
        ]);
        // Assign role if needed (spatie/permission)
        // $user->assignRole('business_owner'); 
    } else {
        echo "User $email found. Resetting password...\n";
        $user->password = bcrypt('password');
        $user->save();
    }

    echo "SUCCESS. User: $email, Password: password\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
