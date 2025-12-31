<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\BusinessCategory;

class FoodOrderingController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $query = Tenant::where('is_public', true)
            ->whereHas('businessCategory', function ($q) {
                $q->where('slug', 'restaurant')->orWhere('slug', 'cafe')->orWhere('slug', 'bar');
            })
            ->with(['businessCategory', 'plan'])
            ->orderByRaw('CASE WHEN is_sponsored = 1 AND (promotion_expires_at IS NULL OR promotion_expires_at > ?) THEN 1 ELSE 0 END DESC', [$now])
            ->orderByDesc('sponsored_ranking')
            ->orderByDesc('created_at');

        if ($request->has('category')) {
            $query->whereHas('businessCategory', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $restaurants = $query->paginate(12);

        // Only show food-related categories
        $categories = BusinessCategory::where('is_active', true)
            ->whereIn('slug', ['restaurant', 'cafe', 'bar', 'fast-food', 'bakery'])
            ->orderBy('order')
            ->get();

        return view('landing.food.index', compact('restaurants', 'categories'));
    }
}
