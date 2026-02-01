<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query()->with('assignedTo');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Allow filtering by assigned staff
        if ($request->has('assigned_to_staff_id')) {
            $query->where('assigned_to_staff_id', $request->assigned_to_staff_id);
        }

        return response()->json([
            'data' => $query->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to_staff_id' => 'nullable|exists:staff,id',
            'due_date' => 'nullable|date',
        ]);

        // Default type
        $validated['type'] = 'general';
        // $validated['created_by_user_id'] = Auth::id(); // Need tenant user logic, skipping for now as per current auth setup

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created',
            'data' => $task
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'assigned_to_staff_id' => 'nullable|exists:staff,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated',
            'data' => $task
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
