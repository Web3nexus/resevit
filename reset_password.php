<?php

use App\Models\Admin;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'web3nexus@gmail.com';
$password = 'Resevit2026!';

$admin = Admin::where('email', $email)->first();

if ($admin) {
    echo "Found Admin: " . $admin->email . " (ID: " . $admin->id . ")\n";

    // The Admin model has a setPasswordAttribute mutator that handles hashing
    $admin->password = $password;
    $admin->save();

    echo "✅ SUCCESS: Password has been reset to: $password\n";
    echo "Please login and change it immediately.\n";
} else {
    echo "❌ ERROR: Admin user '$email' not found.\n";
}
