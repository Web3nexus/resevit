<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\BusinessCategory;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $query = Tenant::where('is_public', true)
            ->with(['businessCategory', 'plan'])
            ->orderByRaw('CASE WHEN is_sponsored = 1 AND (promotion_expires_at IS NULL OR promotion_expires_at > ?) THEN 1 ELSE 0 END DESC', [$now])
            ->orderByDesc('sponsored_ranking')
            ->orderByDesc('created_at');

        if ($request->has('category')) {
            $query->whereHas('businessCategory', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $businesses = $query->paginate(12);
        $categories = BusinessCategory::where('is_active', true)->orderBy('order')->get();

        return view('landing.directory.index', compact('businesses', 'categories'));
    }

    public function show($slug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        if (!$tenant->is_public) {
            abort(404);
        }

        return view('landing.directory.show', compact('tenant'));
    }
}
