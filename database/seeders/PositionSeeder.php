<?php
// database/seeders/PositionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Department;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['department_code' => 'ADMIN', 'name' => 'CEO', 'code' => 'CEO', 'salary_range_min' => 100000, 'salary_range_max' => 200000],
            ['department_code' => 'ADMIN', 'name' => 'Administrative Assistant', 'code' => 'ADM_ASST', 'salary_range_min' => 30000, 'salary_range_max' => 50000],
            ['department_code' => 'HR', 'name' => 'HR Manager', 'code' => 'HR_MGR', 'salary_range_min' => 60000, 'salary_range_max' => 90000],
            ['department_code' => 'HR', 'name' => 'HR Officer', 'code' => 'HR_OFF', 'salary_range_min' => 40000, 'salary_range_max' => 60000],
            ['department_code' => 'FIN', 'name' => 'Finance Manager', 'code' => 'FIN_MGR', 'salary_range_min' => 70000, 'salary_range_max' => 100000],
            ['department_code' => 'FIN', 'name' => 'Accountant', 'code' => 'ACCT', 'salary_range_min' => 50000, 'salary_range_max' => 70000],
            ['department_code' => 'SALES', 'name' => 'Sales Manager', 'code' => 'SALES_MGR', 'salary_range_min' => 60000, 'salary_range_max' => 90000],
            ['department_code' => 'SALES', 'name' => 'Sales Representative', 'code' => 'SALES_REP', 'salary_range_min' => 35000, 'salary_range_max' => 55000],
            ['department_code' => 'INV', 'name' => 'Inventory Manager', 'code' => 'INV_MGR', 'salary_range_min' => 55000, 'salary_range_max' => 80000],
            ['department_code' => 'INV', 'name' => 'Warehouse Supervisor', 'code' => 'WH_SUP', 'salary_range_min' => 40000, 'salary_range_max' => 60000],
            ['department_code' => 'IT', 'name' => 'IT Manager', 'code' => 'IT_MGR', 'salary_range_min' => 70000, 'salary_range_max' => 100000],
            ['department_code' => 'IT', 'name' => 'Software Developer', 'code' => 'DEV', 'salary_range_min' => 50000, 'salary_range_max' => 80000],
            ['department_code' => 'OPS', 'name' => 'Operations Manager', 'code' => 'OPS_MGR', 'salary_range_min' => 60000, 'salary_range_max' => 90000],
            ['department_code' => 'OPS', 'name' => 'Operations Officer', 'code' => 'OPS_OFF', 'salary_range_min' => 35000, 'salary_range_max' => 55000],
        ];

        foreach ($positions as $position) {
            $department = Department::where('code', $position['department_code'])->first();
            if ($department) {
                Position::create([
                    'department_id' => $department->id,
                    'name' => $position['name'],
                    'code' => $position['code'],
                    'salary_range_min' => $position['salary_range_min'],
                    'salary_range_max' => $position['salary_range_max'],
                ]);
            }
        }
    }
}