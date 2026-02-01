<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Resetting stripe_settings...\n";
    DB::connection('landlord')->table('platform_settings')->update([
        'stripe_settings' => null,
    ]);
    echo "Done.\n";
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
