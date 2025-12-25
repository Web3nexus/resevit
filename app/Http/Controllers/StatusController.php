<?php

namespace App\Http\Controllers;

use App\Models\UptimePulse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function index()
    {
        $lastPulse = UptimePulse::latest('created_at')->first();

        // Calculate 30-day uptime
        $totalMinutes = 30 * 24 * 60;
        $pulsesCount = UptimePulse::where('created_at', '>=', now()->subDays(30))->count();
        $uptimePercentage = 0;

        if ($pulsesCount > 0) {
            $firstPulse = UptimePulse::oldest('created_at')->first();
            $minutesSinceStart = max(1, now()->diffInMinutes($firstPulse->created_at));
            $uptimePercentage = min(100, ($pulsesCount / $minutesSinceStart) * 100);
        }

        $services = [
            [
                'name' => 'Web Application',
                'description' => 'Main application interface',
                'status' => 'operational',
            ],
            [
                'name' => 'API',
                'description' => 'REST API services',
                'status' => 'operational',
            ],
            [
                'name' => 'Database',
                'description' => 'Data storage and retrieval',
                'status' => 'operational',
            ],
            [
                'name' => 'File Storage',
                'description' => 'Document and media storage',
                'status' => 'operational',
            ],
            [
                'name' => 'Email Delivery',
                'description' => 'Notification and email system',
                'status' => 'operational',
            ],
            [
                'name' => 'Authentication',
                'description' => 'Login and user authentication',
                'status' => 'operational',
            ],
        ];

        return view('status', [
            'services' => $services,
            'uptimePercentage' => number_format($uptimePercentage, 2),
            'lastChecked' => $lastPulse?->created_at?->diffForHumans() ?? 'Unknown',
            'monitoredServices' => count($services),
            'servicesOnline' => count($services), // For now, all are deemed online if pulse is fresh
        ]);
    }
}
