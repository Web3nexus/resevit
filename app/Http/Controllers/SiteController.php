<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        // If no tenant is active, we are on the central domain
        if (!tenant()) {
            return app(LandingPageController::class)->home();
        }

        $page = SitePage::where('slug', 'index')->where('is_published', true)->first();

        if (!$page) {
            // If no published page exists, we could show a placeholder or redirect
            // For now, let's show a minimal default if it's not published
            return $this->renderDefault();
        }

        return view('site.page', compact('page'));
    }

    protected function renderDefault()
    {
        $tenant = tenant();
        return view('site.default', compact('tenant'));
    }
}
