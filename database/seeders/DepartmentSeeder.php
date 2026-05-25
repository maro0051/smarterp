<?php
// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Administration', 'code' => 'ADMIN', 'description' => 'Administration Department'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Human Resources Department'],
            ['name' => 'Finance & Accounting', 'code' => 'FIN', 'description' => 'Finance and Accounting Department'],
            ['name' => 'Sales & Marketing', 'code' => 'SALES', 'description' => 'Sales and Marketing Department'],
            ['name' => 'Inventory & Warehouse', 'code' => 'INV', 'description' => 'Inventory and Warehouse Department'],
            ['name' => 'Information Technology', 'code' => 'IT', 'description' => 'Information Technology Department'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Operations Department'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}