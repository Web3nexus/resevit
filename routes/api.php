<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PlatformAuthController;
use App\Http\Controllers\Api\V1\InvestorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Public Config / Branding
    Route::get('/bootstrap', [PlatformAuthController::class, 'bootstrap'])->middleware('throttle:60,1');

    // Unified Auth
    Route::post('/auth/register', [PlatformAuthController::class, 'register'])->middleware('throttle:registration');
    Route::post('/auth/login', [PlatformAuthController::class, 'login'])->middleware('throttle:login');

    // Protected User Info
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user()->load(['currentTenant'])->makeVisible(['onboarding_status']);
        });

        Route::patch('/user', function (Request $request) {
            $user = $request->user();
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string',
            ]);
            $user->update($validated);
            return response()->json($user);
        });

        Route::post('/auth/email/resend', [PlatformAuthController::class, 'resendVerificationEmail']);
        Route::post('/auth/password/change', [PlatformAuthController::class, 'changePassword']);

        // Investor Routes
        Route::get('/investor/opportunities', [InvestorController::class, 'opportunities']);
        Route::get('/investor/opportunities/{opportunity}', [InvestorController::class, 'showOpportunity']);
        Route::post('/investor/invest', [InvestorController::class, 'invest']);
        Route::get('/investor/portfolio', [InvestorController::class, 'portfolio']);
        Route::get('/investor/wallet', [InvestorController::class, 'wallet']);

        // --------------------------------------------------------------------------
        // Tenant API Routes (Header-Based for Mobile App)
        // --------------------------------------------------------------------------
        Route::middleware([
            \App\Http\Middleware\InitializeTenancyByHeader::class,
        ])->group(function () {
            // Dashboard
            Route::get('/dashboard/stats', [\App\Http\Controllers\Api\DashboardController::class, 'getStats']);
            Route::get('/dashboard/reservations/recent', [\App\Http\Controllers\Api\DashboardController::class, 'getRecentReservations']);
            Route::get('/dashboard/messages', [\App\Http\Controllers\Api\DashboardController::class, 'getMessages']);

            // Staff
            Route::apiResource('staff', \App\Http\Controllers\Api\V1\StaffController::class);

            // Bookings / Reservations
            Route::apiResource('bookings', \App\Http\Controllers\Api\V1\ReservationController::class);
            Route::get('/reservations', [\App\Http\Controllers\Api\V1\ReservationController::class, 'index']);

            // Orders
            Route::get('/orders', [\App\Http\Controllers\Api\V1\OrderController::class, 'index']);
            Route::get('/orders/{order}', [\App\Http\Controllers\Api\V1\OrderController::class, 'show']);
            Route::patch('/orders/{order}/status', [\App\Http\Controllers\Api\V1\OrderController::class, 'updateStatus']);

            // Finance
            Route::get('/payments', [\App\Http\Controllers\Api\V1\PaymentController::class, 'index']);
            Route::get('/payroll', [\App\Http\Controllers\Api\V1\PayrollController::class, 'index']);
            Route::post('/payroll/staff/{staff}/payout', [\App\Http\Controllers\Api\V1\PayrollController::class, 'processPayout']);

            // Inventory
            Route::apiResource('inventory', \App\Http\Controllers\Api\V1\InventoryController::class);

            // Branches
            Route::apiResource('branches', \App\Http\Controllers\Api\V1\BranchController::class);

            // Debug Tenancy
            Route::get('/debug-tenancy', function () {
                $tenant = tenant();
                return response()->json([
                    'id' => $tenant ? $tenant->id : 'null',
                    'db_name' => $tenant ? $tenant->tenancy_db_name : 'null',
                    'connection' => config('database.default'),
                    'tenant_connection_db' => config('database.connections.tenant.database'),
                    'actual_db' => \Illuminate\Support\Facades\DB::connection('tenant')->getDatabaseName(),
                ]);
            });

            // Chats
            Route::get('/chats', [\App\Http\Controllers\Api\V1\ChatController::class, 'conversations']);
            Route::get('/chats/{chat}/messages', [\App\Http\Controllers\Api\V1\ChatController::class, 'messages']);
            Route::post('/chats/messages', [\App\Http\Controllers\Api\V1\ChatController::class, 'sendMessage']);
        });
    });
});
