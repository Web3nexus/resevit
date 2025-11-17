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
        ]);

        event(new Registered($user));

        $tenant = Tenant::create([
            'name' => $request->business_name,
            'slug' => $request->business_slug,
            'domain' => $request->domain,
            'database_name' => 'resevit_' . Str::slug($request->business_slug),
            'owner_user_id' => $user->id,
        ]);

        CreateTenantDatabase::dispatch($tenant, $request->password);

        Auth::login($user);

        return redirect()->to("http://{$tenant->domain}/dashboard");
    }
}
