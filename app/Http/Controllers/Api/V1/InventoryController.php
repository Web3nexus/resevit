<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::with(['branch', 'menuItem'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inventory
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'item_name' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'min_quantity' => 'nullable|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $item = Inventory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item added to inventory',
            'data' => $item
        ], 201);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inventory updated',
            'data' => $inventory
        ]);
    }
}
