<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterBusinessOwnerRequest;
use App\Jobs\CreateTenantDatabase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterBusinessOwnerRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        event(new Registered($user));

        $tenant = Tenant::create([
            'name' => $request->business_name,
            'slug' => $request->business_slug,
            'domain' => $request->business_slug . '-preview.' . config('tenancy.preview_domain'),
            'database_name' => 'resevit_' . Str::slug($request->business_slug),
            'owner_user_id' => $user->id,
        ]);

        CreateTenantDatabase::dispatch($tenant, $request->password);

        Auth::login($user);

        // Send Welcome Email
        try {
            $user->notify(new \App\Notifications\BaseNotificationImplementation('welcome_registration', [
                'business_name' => $tenant->name,
                'dashboard_url' => "http://{$tenant->domain}/dashboard",
            ]));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // Notify Super Admins of new customer
        try {
            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin> $superAdmins */
            $superAdmins = \App\Models\Admin::whereHas('roles', function ($query) {
                $query->where('name', 'super_admin');
            })->get();

            $superAdmins->each(function (\App\Models\Admin $admin) use ($tenant, $user) {
                $admin->notify(new \App\Notifications\SuperAdminNewCustomerNotification([
                    'business_name' => $tenant->name,
                    'owner_name' => $user->name,
                    'owner_email' => $user->email,
                    'registration_date' => now()->format('F j, Y g:i A'),
                    'plan_name' => 'Free Trial',
                    'admin_url' => config('app.url') . '/securegate/users',
                ]));
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send super admin notification: ' . $e->getMessage());
        }

        return redirect()->to("http://{$tenant->domain}/dashboard");
    }
}
