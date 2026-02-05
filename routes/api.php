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
            return $request->user()->makeVisible(['onboarding_status']);
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
    });
});
