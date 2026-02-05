<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffWorkLog;
use App\Models\TenantUser;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StaffDemoSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        if (!$branch)
            return;

        // 1. Link Users to Staff profiles
        $managerUser = TenantUser::where('email', 'manager@local.test')->first();
        if ($managerUser) {
            $managerStaff = Staff::updateOrCreate(
                ['user_id' => $managerUser->id],
                [
                    'branch_id' => $branch->id,
                    'position' => 'Restaurant Manager',
                    'phone' => '+1234567890',
                    'address' => '123 Manager St, Food City',
                    'hourly_rate' => 25.00,
                    'status' => 'active',
                ]
            );

            // Active work log for manager
            StaffWorkLog::firstOrCreate(
                ['staff_id' => $managerStaff->id, 'check_out' => null],
                ['check_in' => Carbon::now()->subHours(2)]
            );
        }

        $staffUser = TenantUser::where('email', 'staff@local.test')->first();
        if ($staffUser) {
            $waiterStaff = Staff::updateOrCreate(
                ['user_id' => $staffUser->id],
                [
                    'branch_id' => $branch->id,
                    'position' => 'Senior Waiter',
                    'phone' => '+1987654321',
                    'address' => '456 Waiter Ave, Food City',
                    'hourly_rate' => 15.00,
                    'status' => 'active',
                ]
            );

            // Completed work log for yesterday
            StaffWorkLog::create([
                'staff_id' => $waiterStaff->id,
                'check_in' => Carbon::yesterday()->setTime(9, 0),
                'check_out' => Carbon::yesterday()->setTime(17, 0),
                'duration_minutes' => 480,
            ]);
        }
    }
}
