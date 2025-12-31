<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffPayout;
use App\Models\TenantUser;
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
            $user = TenantUser::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password123'),
            ]);

            // 2. Set Team ID for Spatie
            if (isset($data['branch_id'])) {
                setPermissionsTeamId($data['branch_id']);
            }

            // 3. Assign roles
            $roles = $data['roles'] ?? [$role];
            $user->syncRoles($roles);

            // 4. Assign permissions
            if (isset($data['user']['permissions'])) {
                $user->syncPermissions($data['user']['permissions']);
            }

            // 5. Create staff record
            $staff = Staff::create([
                'user_id' => $user->id,
                'branch_id' => $data['branch_id'],
                'position' => $data['position'] ?? $role,
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
     * Update staff member details.
     *
     * @param Staff $staff
     * @param array $data
     * @return Staff
     */
    public function updateStaff(Staff $staff, array $data): Staff
    {
        return DB::transaction(function () use ($staff, $data) {
            // 1. Set Team ID for Spatie scoping
            setPermissionsTeamId($staff->branch_id);

            // 2. Update staff record
            $staff->update($data);

            // 3. Update user
            if ($staff->user) {
                $userData = [];
                if (isset($data['name']))
                    $userData['name'] = $data['name'];
                if (isset($data['email']))
                    $userData['email'] = $data['email'];
                if (!empty($data['password']))
                    $userData['password'] = Hash::make($data['password']);

                if (!empty($userData)) {
                    $staff->user->update($userData);
                }

                // 4. Sync roles
                if (isset($data['roles'])) {
                    $staff->user->syncRoles($data['roles']);
                }

                // 5. Sync permissions
                if (isset($data['user']['permissions'])) {
                    $staff->user->syncPermissions($data['user']['permissions']);
                }
            }

            return $staff->fresh();
        });
    }

    /**
     * Calculate payout amount based on hourly rate and hours worked.
     */
    public function calculatePayout(Staff $staff, int $hours): float
    {
        return $staff->hourly_rate * $hours;
    }

    /**
     * Record a payout for a staff member.
     */
    public function recordPayout(Staff $staff, array $data): StaffPayout
    {
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
     * Get staff statistics.
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
