<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reflector = new ReflectionClass(\Filament\Tables\Table::class);
$methods = $reflector->getMethods();

foreach ($methods as $method) {
    echo $method->getName() . "\n";
    if ($method->getName() === 'actions') {
        echo "FOUND actions method. Checking docblock...\n";
        echo $method->getDocComment() . "\n";
        foreach ($method->getAttributes() as $attribute) {
            echo "Attribute: " . $attribute->getName() . "\n";
        }
    }
}
