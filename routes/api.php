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
            $user = $request->user();
            \Illuminate\Support\Facades\Log::info('DEBUG: /user endpoint hit', [
                'user_id' => $user->id,
                'email' => $user->email,
                'connection' => $user->getConnectionName(),
            ]);

            // 1. Determine Tenant Context
            $tenant = \Illuminate\Support\Facades\DB::connection('landlord')
                ->table('tenants')
                ->where('owner_user_id', $user->id)
                ->first();

            if (!$tenant) {
                // Check if staff has a branch linked to a tenant
                // Search for ANY tenant where this user might be added.
                // For now, let's keep it simple and just find the first tenant they might belong to.
                // In a real system, we'd have a users_tenants pivot or branch_id.
            }

            $tenantId = $tenant ? $tenant->id : null;

            // 2. Prepare User Data
            $user->makeVisible(['onboarding_status']);

            // Determine effective role for App (matches PlatformAuthController logic)
            $role = 'staff';
            if ($user->hasRole('Super Admin')) {
                $role = 'super_admin';
            } elseif ($user->hasRole('Business Owner')) {
                $role = 'business_owner';
            }

            $userData = $user->toArray();
            $userData['role'] = $role;
            $userData['roles'] = $user->getRoleNames();
            $userData['permissions'] = $user->getAllPermissions()->pluck('name');
            $userData['avatar_url'] = \App\Helpers\StorageHelper::getUrl($user->avatar_url);
            $userData['tenant_id'] = $tenantId;

            // Add current_tenant as a map for Flutter User.fromJson
            if ($tenant) {
                $userData['current_tenant'] = [
                    'id' => $tenantId,
                    'name' => $tenant->name,
                    'domain' => $tenant->domain,
                    'slug' => $tenant->slug,
                ];
            } else {
                $userData['current_tenant'] = null;
            }

            \Illuminate\Support\Facades\Log::info('DEBUG: /user returning data', [
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
                'role' => $role,
                'has_avatar' => $user->avatar_url ? 'YES' : 'NO'
            ]);

            return response()->json($userData);
        });

        Route::patch('/user', function (Request $request) {
            $user = $request->user();
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string',
                'mobile' => 'nullable|string',
            ]);
            $user->update($validated);
            return response()->json($user);
        });

        Route::post('/user/avatar', function (Request $request) {
            $request->validate([
                'avatar' => 'required|image|max:2048',
            ]);

            $user = $request->user();
            $path = $request->file('avatar')->store('avatars', 'public');

            $user->update(['avatar_url' => $path]);

            return response()->json([
                'success' => true,
                'avatar_url' => \App\Helpers\StorageHelper::getUrl($path),
            ]);
        });

        Route::get('/notifications', function (Request $request) {
            $notifications = $request->user()->notifications()->latest()->limit(20)->get();
            $unreadCount = $request->user()->unreadNotifications()->count();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        });

        Route::post('/notifications/{id}/read', function (Request $request, $id) {
            $notification = $request->user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            return response()->json(['success' => true]);
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
