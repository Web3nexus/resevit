<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function categories()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function items(Request $request)
    {
        $query = MenuItem::query()
            ->with(['variants', 'addons'])
            ->where('is_active', true)
            ->where('is_available', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->get();

        return response()->json([
            'data' => $items
        ]);
    }

    public function showItem(MenuItem $item)
    {
        $item->load(['variants', 'addons']);

        return response()->json([
            'data' => $item
        ]);
    }

    public function toggleAvailability(MenuItem $item)
    {
        $item->update(['is_available' => !$item->is_available]);

        return response()->json([
            'message' => 'Item availability updated',
            'data' => $item
        ]);
    }
}
