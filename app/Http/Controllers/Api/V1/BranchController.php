<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $branches
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'opening_hours' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $branch = Branch::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Branch created successfully',
            'data' => $branch
        ], 201);
    }

    public function show(Branch $branch)
    {
        return response()->json([
            'success' => true,
            'data' => $branch
        ]);
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'opening_hours' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Branch updated successfully',
            'data' => $branch
        ]);
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return response()->json([
            'success' => true,
            'message' => 'Branch deleted successfully'
        ]);
    }

    /**
     * Update working hours for a branch or globally if needed.
     */
    public function updateHours(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'opening_hours' => 'required|array',
        ]);

        $branch->update(['opening_hours' => $validated['opening_hours']]);

        return response()->json([
            'success' => true,
            'message' => 'Working hours updated successfully',
            'data' => $branch
        ]);
    }
}
