<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use Spatie\Permission\Models\Role;

// Find the tenant for 'manager3.resevit.test'
$tenantId = \Illuminate\Support\Facades\DB::table('domains')
    ->where('domain', 'manager3.resevit.test')
    ->value('tenant_id');

if (!$tenantId) {
    echo "Tenant 'manager3.resevit.test' not found.\n";
    return;
}

$tenant = Tenant::find($tenantId);

tenancy()->initialize($tenant);

echo "Tenant: " . $tenant->id . "\n";
echo "Database: " . $tenant->database_name . "\n\n";

$users = TenantUser::with('roles')->get();

foreach ($users as $user) {
    echo "User: " . $user->name . " (" . $user->email . ")\n";
    echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "----------------------------------------\n";
}
