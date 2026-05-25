<?php
// database/seeders/LeaveTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Annual Leave', 'code' => 'ANNUAL', 'days_per_year' => 20, 'description' => 'Paid annual vacation leave'],
            ['name' => 'Sick Leave', 'code' => 'SICK', 'days_per_year' => 12, 'description' => 'Paid sick leave for medical reasons'],
            ['name' => 'Maternity Leave', 'code' => 'MAT', 'days_per_year' => 90, 'description' => 'Maternity leave for expecting mothers'],
            ['name' => 'Paternity Leave', 'code' => 'PAT', 'days_per_year' => 10, 'description' => 'Paternity leave for new fathers'],
            ['name' => 'Bereavement Leave', 'code' => 'BEREAVE', 'days_per_year' => 5, 'description' => 'Leave for bereavement'],
            ['name' => 'Unpaid Leave', 'code' => 'UNPAID', 'days_per_year' => 30, 'description' => 'Unpaid personal leave'],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}