<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\ChatController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant API Routes
|--------------------------------------------------------------------------
|
| API routes for the tenant application.
|
*/

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api/v1')->group(function () {

    // Public/Guest routes (if any)
    Route::get('/menu/categories', [MenuController::class, 'categories']);
    Route::get('/menu/items', [MenuController::class, 'items']);
    Route::get('/menu/items/{item}', [MenuController::class, 'showItem']);

    // Protected routes - require authentication
    Route::middleware('auth:sanctum')->group(function () {
        // Menu Management
        Route::post('/menu/items/{item}/toggle-availability', [MenuController::class, 'toggleAvailability']);

        // Order Endpoints
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

        // Reservation Endpoints
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::get('/reservations/availability', [ReservationController::class, 'availability']);
        Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);

        // Branch Endpoints
        Route::get('/branches', [ReservationController::class, 'branches']);

        // Task Endpoints
        Route::apiResource('tasks', TaskController::class);

        // Tables/Floor Plan Endpoints (placeholder for now)
        Route::get('/tables', function () {
            return response()->json([
                'data' => [],
                'message' => 'Floor plan feature coming soon'
            ]);
        });

        // Chat Endpoints
        Route::get('/chats', [ChatController::class, 'conversations']);
        Route::get('/chats/{chat}/messages', [ChatController::class, 'messages']);
        Route::post('/chats/messages', [ChatController::class, 'sendMessage']);

        // Stats Endpoint
        Route::get('/stats', [\App\Http\Controllers\Api\V1\StatsController::class, 'index']);

        // Staff Management
        Route::apiResource('staff', \App\Http\Controllers\Api\V1\StaffController::class);
    });
});
