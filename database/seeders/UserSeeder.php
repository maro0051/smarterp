<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@smarterp.com',
            'password' => Hash::make('password123'),
            'employee_id' => 'EMP001',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'address' => '123 Admin Street, City, Country',
            'emergency_contact' => '+0987654321',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        $department = Department::first();
        $position = Position::first();

        if ($department && $position) {
            Employee::create([
                'user_id' => $superAdmin->id,
                'department_id' => $department->id,
                'position_id' => $position->id,
                'employee_code' => 'EMP001',
                'joining_date' => '2020-01-01',
                'salary' => 100000.00,
                'employment_type' => 'full_time',
                'employment_status' => 'active',
                'bank_name' => 'Global Bank',
                'bank_account' => '1234567890',
                'tax_id' => 'TAX001',
                'social_security_number' => 'SSN001',
            ]);
        }

        // Create other users for testing
        $roles = ['Admin', 'HR Manager', 'Accountant', 'Inventory Manager', 'Sales Manager', 'Employee'];
        
        foreach ($roles as $index => $role) {
            $user = User::create([
                'name' => $role . ' User',
                'email' => strtolower(str_replace(' ', '', $role)) . '@smarterp.com',
                'password' => Hash::make('password123'),
                'employee_id' => 'EMP00' . ($index + 2),
                'phone' => '+123456789' . ($index + 1),
                'date_of_birth' => '199' . ($index + 1) . '-01-01',
                'gender' => $index % 2 == 0 ? 'male' : 'female',
                'address' => '123 ' . $role . ' Street, City, Country',
                'emergency_contact' => '+098765432' . ($index + 1),
                'is_active' => true,
            ]);
            $user->assignRole($role);

            if ($department && $position) {
                Employee::create([
                    'user_id' => $user->id,
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'employee_code' => 'EMP00' . ($index + 2),
                    'joining_date' => '202' . ($index + 1) . '-01-01',
                    'salary' => 50000.00 + ($index * 10000),
                    'employment_type' => 'full_time',
                    'employment_status' => 'active',
                    'bank_name' => 'Global Bank',
                    'bank_account' => '987654321' . $index,
                    'tax_id' => 'TAX00' . ($index + 1),
                    'social_security_number' => 'SSN00' . ($index + 1),
                ]);
            }
        }
    }
}