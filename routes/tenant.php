<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Log;

Log::info('Tenant routes file is being loaded.');

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::get('/impersonate/enter', [\App\Http\Controllers\ImpersonationController::class, 'enter'])->name('impersonate.enter');
    Route::get('/impersonate/leave', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('impersonate.leave');

    Route::get('/menu', \App\Livewire\RestaurantMenu::class)->name('tenant.menu');
    Route::get('/checkout', \App\Livewire\RestaurantCheckout::class)->name('tenant.checkout');
    Route::get('/order/{order}', \App\Livewire\OrderStatus::class)->name('tenant.order.status');
});
