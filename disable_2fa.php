<?php

use App\Models\Admin;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = Admin::first();
if ($admin) {
    $admin->two_factor_secret = null;
    $admin->two_factor_confirmed_at = null;
    $admin->save();
    echo "2FA disabled for admin: " . $admin->email . "\n";
} else {
    echo "No admin found.\n";
}
