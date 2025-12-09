<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\CreateTenantDatabase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'role' => ['required', Rule::in(['business_owner', 'investor', 'customer', 'staff'])],
            'restaurant_name' => ['required_if:role,business_owner', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Block registration for staff members
        if ($request->role === 'staff') {
            return redirect()->back()->withErrors(['role' => 'Staff members must be registered by their employer.'])->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the selected role to the user
        $user->assignRole($request->role);

        event(new Registered($user));

        Auth::login($user);

        // Handle Business Owner registration
        if ($request->role === 'business_owner') {
            $slug = Str::slug($request->restaurant_name);
            $domain = $slug . '.' . config('tenancy.central_domains')[0] ?? 'localhost';

            $tenant = Tenant::create([
                'name' => $request->restaurant_name,
                'slug' => $slug,
                'domain' => $domain,
                'owner_user_id' => $user->id,
            ]);

            $tenant->users()->attach($user);

            CreateTenantDatabase::dispatch($tenant);

            return redirect()->to("http://{$tenant->domain}/dashboard");
        }

        // Redirect Investor/Customer to their respective panels
        return match ($request->role) {
            'investor' => redirect()->intended(route('filament.invest.pages.dashboard')),
            'customer' => redirect()->intended(route('filament.customer.pages.dashboard')),
            default => redirect('/'),
        };
    }
}
