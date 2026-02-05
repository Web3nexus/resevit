<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('user')->get();

        $data = $staff->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->user->name ?? 'Unknown',
                'email' => $member->user->email ?? '',
                'role' => $member->position,
                'position' => $member->position,
                'phone' => $member->phone,
                'address' => $member->address,
                'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : null,
                'hourly_rate' => $member->hourly_rate,
                'profile_image' => $member->user->profile_photo_url ?? null,
                'is_active' => $member->status === 'active',
                'status' => $member->status,
                'hire_date' => $member->hire_date ? $member->hire_date->format('Y-m-d') : null,
                'stats' => $this->getStaffStats($member),
            ];
        });

        return response()->json(['data' => $data]);
    }

    private function getStaffStats($member)
    {
        // Mocking stats logic based on work logs if they exist, or just placeholder for now
        // Real implementation would sum worked minutes
        return [
            'active_hours' => 45, // Placeholder
            'total_earned' => ($member->hourly_rate ?? 0) * 45,
            'hours_worked' => 45,
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:tenant.users,email',
            'password' => 'required|min:8',
            'position' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'hourly_rate' => 'nullable|numeric',
            'branch_id' => 'required|exists:tenant.branches,id',
        ]);

        try {
            DB::beginTransaction();

            $user = TenantUser::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $staff = Staff::create([
                'user_id' => $user->id,
                'branch_id' => $validated['branch_id'],
                'position' => $validated['position'],
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'hourly_rate' => $validated['hourly_rate'] ?? 0,
                'status' => 'active',
                'hire_date' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Staff member created successfully',
                'data' => $staff->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create staff member', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'position' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'hourly_rate' => 'nullable|numeric',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        try {
            DB::beginTransaction();

            if (isset($validated['name'])) {
                $staff->user->update(['name' => $validated['name']]);
            }

            $staff->update(array_filter([
                'position' => $validated['position'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'status' => $validated['status'] ?? null,
            ]));

            DB::commit();

            return response()->json([
                'message' => 'Staff member updated successfully',
                'data' => $staff->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update staff member', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->json(['message' => 'Staff member deleted']);
    }
}
