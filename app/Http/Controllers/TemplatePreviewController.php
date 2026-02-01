<?php

namespace App\Http\Controllers;

use App\Models\WebsiteTemplate;

class TemplatePreviewController extends Controller
{
    public function show($slug)
    {
        $template = WebsiteTemplate::where('slug', $slug)->firstOrFail();

        // Use default content for preview
        $content = $template->default_content ?? [];

        // Ensure content is an array
        if (is_string($content)) {
            $content = json_decode($content, true) ?? [];
        }

        $viewName = 'website-templates.'.$template->slug;

        if (! view()->exists($viewName)) {
            return response()->view('website-templates.placeholder', ['template' => $template], 200);
        }

        return view($viewName, [
            'content' => $content,
            'template' => $template,
            'settings' => [], // Default settings
        ]);
    }
}
