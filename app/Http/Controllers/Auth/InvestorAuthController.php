<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InvestorAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.investor.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign investor role if using Spatie
        if (method_exists($user, 'assignRole')) {
            try { $user->assignRole('investor'); } catch (\Throwable $e) { /* ignore if role missing */ }
        }

        Auth::login($user);
        return redirect()->to('/invest/dashboard');
    }

    public function showLogin()
    {
        return view('auth.investor.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/invest/dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
}
