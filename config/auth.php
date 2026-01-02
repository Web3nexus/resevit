<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        // Business Owner & Tenant Users
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Super Admin Panel (/securegate)
        'securegate' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        // Investor Panel (/invest)
        'investor' => [
            'driver' => 'session',
            'provider' => 'investors',
        ],

        // Customer Panel (/customer)
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],

        // Influencer Panel (/influencer)
        'influencer' => [
            'driver' => 'session',
            'provider' => 'influencers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Super Admin provider
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        // Investor provider
        'investors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Investor::class,
        ],

        // Customer provider
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],

        // Influencer provider
        'influencers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Influencer::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
