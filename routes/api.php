<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PlatformAuthController;

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
    Route::get('/bootstrap', [PlatformAuthController::class, 'bootstrap']);

    // Unified Auth
    Route::post('/auth/register', [PlatformAuthController::class, 'register']);
    Route::post('/auth/login', [PlatformAuthController::class, 'login']);

    // Protected User Info
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});
