<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// dd('Web routes loaded');

Route::get('/', [\App\Http\Controllers\SiteController::class, 'index'])->name('home');
Route::middleware(['platform.protect'])->group(function () {
    Route::get('/pricing', [LandingPageController::class, 'pricing'])->name('pricing');

    // Directory Routes
    Route::get('/directory', [\App\Http\Controllers\DirectoryController::class, 'index'])->name('directory.index');
    Route::get('/directory/{slug}', [\App\Http\Controllers\DirectoryController::class, 'show'])->name('directory.show');

    // Food Ordering Directory
    Route::get('/food', [\App\Http\Controllers\FoodOrderingController::class, 'index'])->name('food.index');
    Route::get('/features', [LandingPageController::class, 'features'])->name('features');
    Route::get('/integrations', [LandingPageController::class, 'integrations'])->name('integrations');
    Route::get('/about', [LandingPageController::class, 'about'])->name('about');
    Route::get('/contact', [LandingPageController::class, 'contact'])->name('contact');
    Route::post('/contact/submit', [LandingPageController::class, 'submitContact'])->name('contact.submit');
    Route::post('/newsletter/subscribe', [LandingPageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
    Route::get('/faq', [LandingPageController::class, 'faq'])->name('faq');
    Route::get('/legal/{slug}', [App\Http\Controllers\LegalController::class, 'show'])->name('legal.show');
    Route::get('/cookie-policy', function () {
        return view('landing.cookie-policy');
    })->name('landing.cookie-policy');
    Route::get('/resources', [LandingPageController::class, 'resources'])->name('resources');
    Route::get('/resources/{slug}', [LandingPageController::class, 'resourceShow'])->name('resources.show');
    Route::get('/status', [App\Http\Controllers\StatusController::class, 'index'])->name('status');
    Route::get('/docs', [App\Http\Controllers\DocsController::class, 'index'])->name('docs.index');
    Route::get('/docs/{slug}', [App\Http\Controllers\DocsController::class, 'show'])->name('docs.show');
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
    Route::get('dashboard-redirect', \App\Http\Controllers\Auth\DashboardRedirectController::class)->name('dashboard.redirect');
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Wallet Deposit
    Route::get('wallet/deposit', [\App\Http\Controllers\Finance\WalletDepositController::class, 'deposit'])->name('wallet.deposit');
    Route::get('wallet/deposit/success', [\App\Http\Controllers\Finance\WalletDepositController::class, 'success'])->name('wallet.deposit.success');
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
    // We removed strict tenancy middleware here to avoid crashes on tenant domains
    // The Controller handles identifying the tenant context if needed, or we rely on the host
])->group(function () {
    Route::get('/impersonate/enter', [\App\Http\Controllers\ImpersonationController::class, 'enter'])->name('impersonate.enter');
    Route::get('/impersonate/leave', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('impersonate.leave');
});

// Temporary Seed/Fix Route
Route::get('/debug/seed-features', function () {
    // 1. Run Seeder
    try {
        $seeder = new \Database\Seeders\PricingSeeder();
        $seeder->run();
    } catch (\Exception $e) {
    }

    // 2. Clear Caches
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('filament:upgrade');

    // 3. Optional: Force logout by clearing current session if user requests it via ?reset=1
    if (request()->has('reset')) {
        \Illuminate\Support\Facades\Auth::logout();
        session()->flush();
        session()->regenerate(true);
        return "System Deep-Cleaned! You have been logged out. <br><br> 
                <b>ACTION:</b> Please perform a <b>HARD REFRESH (Cmd+Shift+R)</b> and log in again.";
    }

    return "System Repaired! Caches cleared and Filament upgraded. <br><br> 
            <b>IMPORTANT:</b> Please perform a <b>HARD REFRESH (Cmd+Shift+R or Ctrl+F5)</b> in your browser now. <br>
            If the UI is still stuck, try <a href='/debug/seed-features?reset=1'>Deep Clean (Logout)</a>.";
});
