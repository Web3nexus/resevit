<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\TenantCreatorService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegistrationRequest $request, TenantCreatorService $tenantCreator): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        event(new Registered($user));

        Auth::login($user);

        if ($request->role === 'business_owner') {
            $tenant = $tenantCreator->createTenant($user, $request->restaurant_name);
            $domain = $tenant->domains->first()->domain;

            return redirect()->to("//{$domain}/dashboard");
        }

        return match ($request->role) {
            'investor' => redirect()->intended(route('filament.invest.pages.dashboard')),
            'customer' => redirect()->intended(route('filament.customer.pages.dashboard')),
            default => redirect('/'),
        };
    }
}
