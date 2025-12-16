<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = new \App\Models\Tenant([
    'name' => 'Test',
    'slug' => 'test-slug-debug',
    'database_name' => 'test_db_name',
    'domain' => 'test.domin.com'
]);

echo "Attempted DB Name: test_db_name\n";
echo "Actual DB Name Attribute: " . $t->database_name . "\n";
echo "Is Fillable: " . (in_array('database_name', $t->getFillable()) ? 'Yes' : 'No') . "\n";
echo "Attributes: " . json_encode($t->getAttributes()) . "\n";

// Check if HasDatabase trait has internal logic
echo "Traits: " . implode(', ', array_keys((new ReflectionClass($t))->getTraits())) . "\n";
