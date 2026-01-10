<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Send Security Alert
            try {
                $user->notify(new \App\Notifications\NewLoginNotification([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'device' => \Illuminate\Support\Str::contains($request->userAgent(), 'Mobile') ? 'Mobile' : 'Desktop',
                    'location' => 'Unknown', // Could use a GeoIP service here
                ]));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send login alert: ' . $e->getMessage());
            }

            // Check if 2FA is enabled
            if (method_exists($user, 'hasTwoFactorEnabled') && $user->hasTwoFactorEnabled()) {
                // Store user ID in session and log them out
                $request->session()->put('2fa:user:id', $user->id);
                $request->session()->put('2fa:user:type', get_class($user));
                $request->session()->put('2fa:remember', $request->boolean('remember'));

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                // Re-add the 2fa data after invalidation
                $request->session()->put('2fa:user:id', $user->id);
                $request->session()->put('2fa:user:type', get_class($user));
                $request->session()->put('2fa:remember', $request->boolean('remember'));

                return redirect()->route('2fa.challenge');
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
