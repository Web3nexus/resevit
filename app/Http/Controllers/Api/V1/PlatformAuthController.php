<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\User;
use App\Models\Customer;
use App\Models\Investor; // Assuming Investor model exists
use App\Models\Tenant;
use App\Models\PricingPlan;
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
        $settings = \App\Models\PlatformSetting::current();

        // 1. Branding
        $branding = [
            'primaryColor' => '#E2B33B', // Default brand color
            'logoUrl' => $settings->logo_path ? StorageHelper::getUrl($settings->logo_path) : null,
        ];

        // 2. Tenant Info (Landlord level defaults)
        $tenantInfo = [
            'id' => 'landlord',
            'name' => config('app.name', 'Resevit'),
            'currency' => 'USD',
            'timezone' => 'UTC',
        ];

        // 3. Features Toggle
        $features = [
            'registration' => true,
            'search' => true,
            'business_owner_reg' => true,
            'investor_reg' => true,
        ];

        // 4. Pricing Plans (for the "pricing or something" part)
        $plans = \App\Models\PricingPlan::where('is_active', true)->orderBy('order')->get();

        return response()->json([
            'branding' => $branding,
            'tenant' => $tenantInfo,
            'features' => $features,
            'email_settings' => $settings->email_settings ?? [],
            'pricing_plans' => $plans, // Added for UI needs
            'app_name' => config('app.name'),
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

                $roles = $user->getRoleNames(); // Returns Collection
                $permissions = $user->getAllPermissions()->pluck('name');

                // Determine effective role for App Routing
                $role = 'staff';
                if ($user->hasRole('Super Admin')) {
                    $role = 'super_admin';
                } elseif ($user->hasRole('Business Owner') || $type === 'business_owner') {
                    $role = 'business_owner';
                }

                $onboardingStatus = 'active';

                if ($role === 'business_owner') {
                    // Check tenant status
                    $tenant = Tenant::where('owner_user_id', $user->id)->first();
                    if ($tenant) {
                        $onboardingStatus = $tenant->onboarding_status ?? 'active';
                    }
                }

                return response()->json([
                    'token' => $token,
                    'user' => $user,
                    'role' => $role, // Primary role for routing
                    'roles' => $roles, // All assigned roles
                    'permissions' => $permissions, // All permissions
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

        try {
            if ($type === 'business_owner') {
                $request->validate([
                    'email' => 'unique:users,email',
                    'business_name' => 'required|string',
                    'business_slug' => 'required|string|unique:tenants,slug',
                    'domain' => 'nullable|string|unique:tenants,domain',
                    'name' => 'required|string',
                    'country' => 'required|string',
                    'phone' => 'required|string',
                    'phone_country_code' => 'required|string',
                    'staff_range' => 'required|string',
                ]);

                $domain = $request->domain ?? $request->business_slug . '.' . config('tenancy.preview_domain');

                \Illuminate\Support\Facades\DB::beginTransaction();

                // Logic adapted from RegisteredUserController
                $user = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password, // Model setter/cast handles hashing
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'mobile' => $request->phone,
                ]);

                // Associate with role if applicable
                if (class_exists(\Spatie\Permission\Models\Role::class)) {
                    $roleExists = \Spatie\Permission\Models\Role::where('name', 'Business Owner')->exists();
                    if ($roleExists) {
                        $user->assignRole('Business Owner');
                    }
                }

                $tenant = \App\Models\Tenant::create([
                    'name' => $request->business_name,
                    'slug' => $request->business_slug,
                    'domain' => $domain,
                    'owner_user_id' => $user->id,
                    'mobile' => $request->phone,
                    'country' => $request->country,
                    'data' => [
                        'phone_country_code' => $request->phone_country_code,
                    ],
                    'staff_range' => $request->staff_range,
                    'onboarding_status' => 'pending_setup',
                ]);

                $token = $user->createToken('auth-token')->plainTextToken;

                \Illuminate\Support\Facades\DB::commit();

                // Side effects after commit (to allow registration even if email fails - or keep it inside if it must succeed)
                // Let's keep email after commit so if it fails, the user is still registered and can verify later.
                try {
                    $settings = \App\Models\PlatformSetting::current();
                    if ($settings->email_settings['enable_registration_email'] ?? true) {
                        $user->sendEmailVerificationNotification();
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Registration Email failed but user created: ' . $e->getMessage());
                }

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
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registration failed: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }

        try {
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
                    'onboarding_status' => 'active',
                ], 201);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Customer Registration Error: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed: ' . $e->getMessage()], 500);
        }

        try {
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
                        'onboarding_status' => 'active',
                    ], 201);
                } else {
                    return response()->json(['message' => 'Investor registration not implemented'], 501);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Investor Registration Error: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Invalid type'], 400);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return view('emails.rendered-text', ['content' => 'Invalid user.']);
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return view('emails.rendered-text', ['content' => 'Invalid verification link.']);
        }

        if ($user->hasVerifiedEmail()) {
            // Already verified
            return view('emails.rendered-text', ['content' => 'Email already verified! You can return to the app.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return view('emails.rendered-text', ['content' => 'Email verified successfully! You can return to the app.']);
    }

    /**
     * Resend the email verification notification.
     */
    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    }
}
