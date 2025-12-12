<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffPayout;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    /**
     * Create a new staff member with user account and role.
     *
     * @param array $data
     * @param string $role
     * @return Staff
     */
    public function createStaff(array $data, string $role): Staff
    {
        return DB::transaction(function () use ($data, $role) {
            // 1. Create user in tenant DB
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password123'), // Default password if not provided
            ]);

            // 2. Assign role via Spatie
            $user->assignRole($role);

            // 3. Create staff record
            $staff = Staff::create([
                'user_id' => $user->id,
                'position' => $role,
                'phone' => $data['phone'] ?? null,
                'emergency_contact' => $data['emergency_contact'] ?? null,
                'hire_date' => $data['hire_date'] ?? now(),
                'hourly_rate' => $data['hourly_rate'] ?? 0,
                'status' => $data['status'] ?? 'active',
                'availability' => $data['availability'] ?? null,
            ]);

            return $staff;
        });
    }

    /**
     * Calculate payout amount based on hourly rate and hours worked.
     *
     * @param Staff $staff
     * @param int $hours
     * @return float
     */
    public function calculatePayout(Staff $staff, int $hours): float
    {
        return $staff->hourly_rate * $hours;
    }

    /**
     * Record a payout for a staff member.
     *
     * @param Staff $staff
     * @param array $data
     * @return StaffPayout
     */
    public function recordPayout(Staff $staff, array $data): StaffPayout
    {
        // Calculate amount if hours provided but amount not
        if (!isset($data['amount']) && isset($data['hours_worked'])) {
            $data['amount'] = $this->calculatePayout($staff, $data['hours_worked']);
        }

        return StaffPayout::create([
            'staff_id' => $staff->id,
            'amount' => $data['amount'],
            'payout_date' => $data['payout_date'] ?? now(),
            'hours_worked' => $data['hours_worked'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ]);
    }

    /**
     * Update staff member details.
     *
     * @param Staff $staff
     * @param array $data
     * @return Staff
     */
    public function updateStaff(Staff $staff, array $data): Staff
    {
        $staff->update($data);

        // Update user if name or email changed
        if (isset($data['name']) || isset($data['email'])) {
            $staff->user->update([
                'name' => $data['name'] ?? $staff->user->name,
                'email' => $data['email'] ?? $staff->user->email,
            ]);
        }

        return $staff->fresh();
    }

    /**
     * Get staff statistics.
     *
     * @param Staff $staff
     * @return array
     */
    public function getStaffStats(Staff $staff): array
    {
        return [
            'total_paid' => $staff->total_paid,
            'pending_payout' => $staff->pending_payout,
            'total_hours' => $staff->payouts()->sum('hours_worked'),
            'payouts_count' => $staff->payouts()->count(),
        ];
    }
}
