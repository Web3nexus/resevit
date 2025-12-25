<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Investor;
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
            'email' => 'required|email|unique:investors,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Investor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Automagically hashed in model or via cast
        ]);

        // Assign investor role with investor guard
        $user->assignRole('investor', 'investor');

        Auth::guard('investor')->login($user);

        return redirect()->to('/invest');
    }

    public function showLogin()
    {
        return view('auth.investor.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('investor')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/invest');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
}
