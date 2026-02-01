<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

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
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Public Website
    Route::get('/', [\App\Http\Controllers\TenantWebsiteController::class, 'show'])->name('tenant.home');

    // Admin Access Redirects
    Route::get('/portal', fn () => redirect('/dashboard'));
    Route::get('/office', fn () => redirect('/dashboard'));

    Route::get('/menu', \App\Livewire\RestaurantMenu::class)->name('tenant.menu');
    Route::get('/reservations', \App\Livewire\RestaurantBooking::class)->name('tenant.reservations');
    Route::get('/checkout', \App\Livewire\RestaurantCheckout::class)->name('tenant.checkout');
    Route::get('/order/confirmation/{order}', \App\Livewire\OrderConfirmation::class)->name('tenant.order.confirmation');
    Route::get('/order/{order}', \App\Livewire\OrderStatus::class)->name('tenant.order.status');

    Route::get('/pos/receipt/{order}', [\App\Http\Controllers\Dashboard\ReceiptController::class, 'show'])->name('dashboard.pos.receipt');

    // Social Account Connection
    Route::middleware(['auth'])->group(function () {
        Route::get('/social/connect/{platform}', [\App\Http\Controllers\SocialConnectionController::class, 'redirect'])
            ->name('social.connect');
        Route::get('/social/callback/{platform}', [\App\Http\Controllers\SocialConnectionController::class, 'callback'])
            ->name('social.callback');
    });

    // Social Messaging Webhooks
    Route::match(['get', 'post'], '/webhooks/social/{platform}', [\App\Http\Controllers\SocialWebhookController::class, 'handle'])
        ->name('webhooks.social');
});
