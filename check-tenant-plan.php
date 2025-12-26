#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the tenant
$tenant = \App\Models\Tenant::where('id', 'b15c6236-3b9c-444e-899e-a9a5f70ae72f')->first();

if (!$tenant) {
    echo "Tenant not found!\n";
    exit(1);
}

echo "Tenant: {$tenant->name}\n";
echo "Plan ID: " . ($tenant->plan_id ?? 'NULL') . "\n";

if ($tenant->plan) {
    echo "Plan Name: {$tenant->plan->name}\n";
    echo "Plan Slug: {$tenant->plan->slug}\n";

    echo "\nFeatures for this plan:\n";
    $features = $tenant->plan->features()->get();
    foreach ($features as $feature) {
        echo "  - {$feature->feature_key}: is_included=" . ($feature->pivot->is_included ? 'YES' : 'NO') . "\n";
    }
} else {
    echo "NO PLAN ASSIGNED TO TENANT!\n";
}
