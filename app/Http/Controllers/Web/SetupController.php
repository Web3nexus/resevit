<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetupController extends Controller
{
    /**
     * Show the setup finish page (Place plan selection here)
     */
    public function index()
    {
        // In a real scenario, this view would show pricing plans
        return "
        <html>
            <head><title>Finish Setup</title></head>
            <body style='font-family: sans-serif; text-align: center; padding: 50px;'>
                <h1>Complete Your Registration</h1>
                <p>Select a plan to activate your business account.</p>
                <form method='POST' action='" . route('setup.finish.store') . "'>
                    " . csrf_field() . "
                    <button type='submit' style='padding: 15px 30px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px; font-size: 16px;'>
                        Complete Setup (Simulated Payment)
                    </button>
                </form>
            </body>
        </html>
        ";
    }

    /**
     * Process the setup completion
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $tenant = Tenant::where('owner_user_id', $user->id)->first();

        if ($tenant) {
            $tenant->update(['onboarding_status' => 'active']);
        }

        return "
        <html>
            <body style='font-family: sans-serif; text-align: center; padding: 50px;'>
                <h1 style='color: green;'>Setup Complete!</h1>
                <p>Your account is now active. You may return to the app and refresh.</p>
            </body>
        </html>
        ";
    }
}
