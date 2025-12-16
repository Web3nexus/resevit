<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ImpersonationController extends Controller
{
    public function enter(Request $request)
    {
        $token = $request->query('token');

        if (! $token) {
            abort(403, 'No token provided.');
        }

        $data = Cache::pull("impersonation_token_{$token}");

        if (! $data) {
            abort(403, 'Invalid or expired token.');
        }

        Auth::loginUsingId($data['user_id']);

        session()->put('impersonator_id', $data['admin_id']);
        session()->put('impersonator_guard', $data['original_guard']);

        return redirect()->to('/dashboard'); // Filament dashboard path
    }

    public function leave()
    {
        $impersonatorId = session()->get('impersonator_id');

        if (! $impersonatorId) {
            return redirect()->to('/dashboard');
        }

        Auth::logout();
        session()->forget(['impersonator_id', 'impersonator_guard']);

        // Redirect back to the central domain admin panel
        // We assume the central domain is defined in config('app.url')
        // Or hardcode the securegate path

        $centralUrl = config('app.url');
        return redirect()->to("{$centralUrl}/securegate");
    }
}
