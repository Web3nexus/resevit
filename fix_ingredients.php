<?php

use App\Models\Tenant;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenantId = 'b15c6236-3b9c-444e-899e-a9a5f70ae72f';
$tenant = Tenant::find($tenantId);

if (! $tenant) {
    exit("Tenant not found\n");
}

tenancy()->initialize($tenant);

echo 'Initializing tenancy for: '.$tenant->id."\n";
echo 'Active database: '.DB::connection()->getDatabaseName()."\n";

try {
    $columns = Schema::getColumnListing('menu_items');
    echo "Columns in 'menu_items': ".implode(', ', $columns)."\n";

    if (! in_array('ingredients', $columns)) {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->text('ingredients')->nullable()->after('description');
        });
        echo "Column 'ingredients' added successfully after missing detection.\n";
    } else {
        echo "Column 'ingredients' confirmed to exist in listing.\n";
    }
} catch (\Exception $e) {
    echo 'Error adding column: '.$e->getMessage()."\n";
}
