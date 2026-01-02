<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\User;
use App\Models\Customer;
use App\Models\Investor; // Assuming Investor model exists
use App\Models\Tenant;
use App\Jobs\CreateTenantDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\StorageHelper;

class PlatformAuthController extends Controller
{
    public function bootstrap()
    {
        $settings = PlatformSetting::current();

        return response()->json([
            'app_name' => config('app.name'),
            'logo_url' => $settings->logo_path ? StorageHelper::getUrl($settings->logo_path) : null,
            'hero_title' => $settings->landing_settings['hero_title'] ?? null,
            // Add other branding colors/fonts here if stored in DB
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'type' => 'nullable|in:business_owner,customer,investor,staff',
        ]);

        $credentials = $request->only('email', 'password');
        $type = $request->input('type');

        // 1. Try Business Owner / Staff (Web Guard)
        if (!$type || $type === 'business_owner' || $type === 'staff') {
            if (Auth::guard('web')->attempt($credentials)) {
                $user = Auth::guard('web')->user();
                $token = $user->createToken('auth-token')->plainTextToken;

                $role = $user->hasRole('Super Admin') ? 'super_admin' : 'business_owner';
                $onboardingStatus = 'active';

                if ($role === 'business_owner') {
                    // Check tenant status
                    $tenant = Tenant::where('owner_user_id', $user->id)->first();
                    if ($tenant) {
                        $onboardingStatus = $tenant->onboarding_status ?? 'active';
                    }
                }

                // Determine functionality based on internal logic or separate 'role' column
                // For now, assume Business Owner logic or Super Admin
                return response()->json([
                    'token' => $token,
                    'user' => $user,
                    'role' => $role,
                    'onboarding_status' => $onboardingStatus,
                    'email_verified_at' => $user->email_verified_at,
                    // Note: 'business_owner' user creates a tenant. Staff belongs to tenant.
                    // This logic might need refinement if Staff logins are on Tenant URLs.
                    // But for Central App login:
                ]);
            }
        }

        // 2. Try Customer
        if (!$type || $type === 'customer') {
            if (Auth::guard('customer')->attempt($credentials)) {
                $user = Auth::guard('customer')->user();
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'user' => $user,
                    'role' => 'customer',
                ]);
            }
        }

        // 3. Try Investor
        if (!$type || $type === 'investor') {
            // Check if investor guard exists, otherwise might be manual check
            if (Auth::guard('investor')->attempt($credentials)) {
                $user = Auth::guard('investor')->user();
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'user' => $user,
                    'role' => 'investor',
                ]);
            }
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'type' => 'required|in:business_owner,customer,investor',
            'email' => 'required|email', // Unique validations below
            'password' => 'required|min:8',
        ]);

        $type = $request->type;

        if ($type === 'business_owner') {
            $request->validate([
                'email' => 'unique:users,email',
                'business_name' => 'required|string',
                'business_slug' => 'required|string|unique:tenants,slug',
                'domain' => 'required|string|unique:tenants,domain',
                'name' => 'required|string',
                'country' => 'required|string',
                'phone' => 'required|string',
                'phone_country_code' => 'required|string',
                'staff_range' => 'required|string',
            ]);

            // Logic adapted from RegisteredUserController
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'country' => $request->country,
                // 'mobile' might be redundant if 'phone' is used, but populating both if needed
                'mobile' => $request->phone,
            ]);

            // Assign Business Owner Role?
            // $user->assignRole('Business Owner'); 

            $tenant = Tenant::create([
                'name' => $request->business_name,
                'slug' => $request->business_slug,
                'domain' => $request->domain,
                'database_name' => 'resevit_' . Str::slug($request->business_slug),
                'owner_user_id' => $user->id,
                'data' => [
                    'country' => $request->country,
                    'phone_country_code' => $request->phone_country_code,
                    'phone' => $request->phone,
                ],
                'staff_range' => $request->staff_range,
                'onboarding_status' => 'pending_setup',
            ]);

            event(new \Illuminate\Auth\Events\Registered($user));

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Business registered successfully',
                'token' => $token,
                'user' => $user,
                'role' => 'business_owner',
                'tenant_domain' => $tenant->domain,
                'onboarding_status' => 'pending_setup',
                'email_verified_at' => null,
            ], 201);
        }

        if ($type === 'customer') {
            $request->validate([
                'email' => 'unique:customers,email',
                'name' => 'required|string',
            ]);

            $user = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // $user->assignRole('customer');

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Customer registered successfully',
                'token' => $token,
                'user' => $user,
                'role' => 'customer',
            ], 201);
        }

        if ($type === 'investor') {
            $request->validate([
                'email' => 'unique:investors,email', // Assuming investors table
                'name' => 'required|string',
            ]);

            // Check if Investor model exists. For now, referencing generic.
            // If class doesn't exist, this will error, but implementing placeholder.
            if (class_exists(Investor::class)) {
                $user = Investor::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Investor registered successfully',
                    'token' => $token,
                    'user' => $user,
                    'role' => 'investor',
                ], 201);
            } else {
                return response()->json(['message' => 'Investor registration not implemented'], 501);
            }
        }

        return response()->json(['message' => 'Invalid type'], 400);
    }
}
