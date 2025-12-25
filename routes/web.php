<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// dd('Web routes loaded');

Route::get('/', [LandingPageController::class, 'home'])->name('home');
Route::get('/pricing', [LandingPageController::class, 'pricing'])->name('pricing');
Route::get('/features', [LandingPageController::class, 'features'])->name('features');
Route::get('/integrations', [LandingPageController::class, 'integrations'])->name('integrations');
Route::get('/about', [LandingPageController::class, 'about'])->name('about');
Route::get('/contact', [LandingPageController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [LandingPageController::class, 'submitContact'])->name('contact.submit');
Route::post('/newsletter/subscribe', [LandingPageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/faq', [LandingPageController::class, 'faq'])->name('faq');
Route::get('/legal/{slug}', [App\Http\Controllers\LegalController::class, 'show'])->name('legal.show');
Route::get('/resources', [LandingPageController::class, 'resources'])->name('resources');
Route::get('/resources/{slug}', [LandingPageController::class, 'resourceShow'])->name('resources.show');
Route::get('/status', [App\Http\Controllers\StatusController::class, 'index'])->name('status');
Route::get('/docs', [App\Http\Controllers\DocsController::class, 'index'])->name('docs.index');
Route::get('/docs/{slug}', [App\Http\Controllers\DocsController::class, 'show'])->name('docs.show');

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
    // Unified Registration (Business Owner, Customer, Investor)
    Route::get('register', function () {
        return redirect()->route('filament.dashboard.auth.register');
    })->name('register');

    // Central Login (Redirects to Tenant Dashboard)
    // Central Login (Redirects to Tenant Dashboard)
    Route::get('login', [\App\Http\Controllers\Auth\CentralLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\CentralLoginController::class, 'login']);

    // Password Reset - utilizing standard controller for now, could be replaced with Filament/Livewire later
    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    // OAuth Routes
    Route::get('oauth/{provider}', [OAuthController::class, 'redirect'])->name('oauth.redirect');

    // Named routes for specific providers
    Route::get('auth/google', function () {
        return app(OAuthController::class)->redirect('google');
    })->name('auth.google');

    Route::get('auth/apple', function () {
        return app(OAuthController::class)->redirect('apple');
    })->name('auth.apple');
});

Route::get('oauth/{provider}/callback', [OAuthController::class, 'callback'])->name('oauth.callback');

Route::middleware('auth')->group(function () {
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/debug-auth', function () {
    $user = auth()->user();
    return [
        'is_logged_in' => auth()->check(),
        'user' => $user,
        'session_id' => session()->getId(),
        'tenant' => tenant('id'),
        'domain' => request()->getHost(),
    ];
});

// Impersonation routes (Universal)
Route::middleware([
    'web',
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/impersonate/enter', [\App\Http\Controllers\ImpersonationController::class, 'enter'])->name('impersonate.enter');
    Route::get('/impersonate/leave', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('impersonate.leave');
});
