<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.customer.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Automagically hashed in model or via cast
        ]);

        // Assign customer role with customer guard
        $user->assignRole('customer', 'customer');

        Auth::guard('customer')->login($user);

        return redirect()->to('/customer');
    }

    public function showLogin()
    {
        return view('auth.customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/customer');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
}
