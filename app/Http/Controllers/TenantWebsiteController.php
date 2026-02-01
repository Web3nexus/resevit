<?php

namespace App\Http\Controllers;

use App\Models\TenantWebsite;

class TenantWebsiteController extends Controller
{
    public function show()
    {
        $isHostPreview = str_contains(request()->getHost(), config('tenancy.preview_domain'));
        $isOwner = auth()->check() && tenant('owner_user_id') === auth()->id();

        $website = TenantWebsite::where('tenant_id', tenant('id'))
            ->when((! $isHostPreview && ! $isOwner), fn ($query) => $query->where('is_published', true))
            ->with('template')
            ->firstOrFail();

        // Render the view corresponding to the template
        // Assuming templates are stored in resources/views/website-templates/{slug}.blade.php
        $viewName = 'website-templates.'.$website->template->slug;

        if (! view()->exists($viewName)) {
            abort(404, 'Template view not found.');
        }

        return view($viewName, [
            'website' => $website,
            'template' => $website->template,
            'content' => $website->content,
            'settings' => $website->settings,
        ]);
    }
}
