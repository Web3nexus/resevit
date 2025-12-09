<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\OAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Investor panel auth
Route::prefix('investor')->name('investor.')->middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\Auth\InvestorAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\InvestorAuthController::class, 'register']);

    Route::get('login', [\App\Http\Controllers\Auth\InvestorAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\InvestorAuthController::class, 'login']);
});

// Customer panel auth
Route::prefix('customer')->name('customer.')->middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'register']);

    Route::get('login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'login']);
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // OAuth Routes
    Route::get('oauth/{provider}', [OAuthController::class, 'redirect'])->name('oauth.redirect');
});

Route::get('oauth/{provider}/callback', [OAuthController::class, 'callback'])->name('oauth.callback');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Prevent dashboard registration from being used to create super-admins via web.
// Redirect any /dashboard/register attempts to the dashboard login (admins are created via console).
Route::get('dashboard/register', function () {
    return redirect('/dashboard/login');
})->name('dashboard.register.redirect');
