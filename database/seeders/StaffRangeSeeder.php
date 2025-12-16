<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranges = [
            ['range' => '1-5', 'label' => '1-5 Helpers'],
            ['range' => '6-10', 'label' => '6-10 Staff'],
            ['range' => '11-20', 'label' => '11-20 Staff'],
            ['range' => '21-50', 'label' => '20-50 Staff'],
            ['range' => '50+', 'label' => '50+ Staff'],
        ];

        foreach ($ranges as $range) {
            \App\Models\StaffRange::create($range);
        }
    }
}
