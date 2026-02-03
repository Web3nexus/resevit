<?php

use App\Models\Admin;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Try to find the Super Admin specifically
$admin = Admin::where('email', 'admin@resevit.com')->first();

// Fallback to the first admin if not found
if (!$admin) {
    echo "Super Admin (admin@resevit.com) not found. Checking for any admin...\n";
    $admin = Admin::first();
}

if ($admin) {
    echo "Found Admin: " . $admin->email . " (ID: " . $admin->id . ")\n";

    $admin->two_factor_secret = null;
    $admin->two_factor_confirmed_at = null;
    // Also reset recovery codes just in case
    $admin->two_factor_recovery_codes = null;
    $admin->save();

    echo "✅ SUCCESS: 2FA has been disabled for " . $admin->email . ".\n";
    echo "You should now be able to login with your email and password.\n";
} else {
    echo "❌ ERROR: No Admin users found in the database.\n";
}
