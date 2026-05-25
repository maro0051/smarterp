<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view dashboard',
            
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Employee Management
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // Department Management
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            
            // Position Management
            'view positions',
            'create positions',
            'edit positions',
            'delete positions',
            
            // Attendance
            'view attendance',
            'create attendance',
            'edit attendance',
            
            // Leave Management
            'view leave requests',
            'create leave requests',
            'approve leave requests',
            'reject leave requests',
            
            // Inventory
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view warehouses',
            'create warehouses',
            'edit warehouses',
            'delete warehouses',
            'view stock movements',
            'create stock movements',
            
            // Sales
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view payments',
            'create payments',
            
            // Accounting
            'view expenses',
            'create expenses',
            'approve expenses',
            'delete expenses',
            
            // CRM
            'view leads',
            'create leads',
            'edit leads',
            'delete leads',
            
            // Projects
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            
            // Reports
            'view reports',
            'export reports',
            
            // Settings
            'view settings',
            'edit settings',
            
            // Activity Logs
            'view activity logs',
            
            // Notifications
            'view notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $hrManagerRole = Role::create(['name' => 'HR Manager']);
        $hrManagerRole->givePermissionTo([
            'view dashboard',
            'view employees', 'create employees', 'edit employees',
            'view departments', 'create departments', 'edit departments',
            'view positions', 'create positions', 'edit positions',
            'view attendance', 'create attendance', 'edit attendance',
            'view leave requests', 'create leave requests', 'approve leave requests', 'reject leave requests',
            'view reports', 'export reports',
            'view notifications',
        ]);

        $accountantRole = Role::create(['name' => 'Accountant']);
        $accountantRole->givePermissionTo([
            'view dashboard',
            'view expenses', 'create expenses', 'approve expenses',
            'view invoices', 'create invoices', 'edit invoices',
            'view payments', 'create payments',
            'view reports', 'export reports',
            'view notifications',
        ]);

        $inventoryManagerRole = Role::create(['name' => 'Inventory Manager']);
        $inventoryManagerRole->givePermissionTo([
            'view dashboard',
            'view products', 'create products', 'edit products',
            'view categories', 'create categories', 'edit categories',
            'view suppliers', 'create suppliers', 'edit suppliers',
            'view warehouses', 'create warehouses', 'edit warehouses',
            'view stock movements', 'create stock movements',
            'view reports', 'export reports',
            'view notifications',
        ]);

        $salesManagerRole = Role::create(['name' => 'Sales Manager']);
        $salesManagerRole->givePermissionTo([
            'view dashboard',
            'view customers', 'create customers', 'edit customers',
            'view invoices', 'create invoices', 'edit invoices',
            'view payments', 'create payments',
            'view leads', 'create leads', 'edit leads',
            'view reports', 'export reports',
            'view notifications',
        ]);

        $employeeRole = Role::create(['name' => 'Employee']);
        $employeeRole->givePermissionTo([
            'view dashboard',
            'view leave requests', 'create leave requests',
            'view attendance',
            'view tasks',
            'view notifications',
        ]);
    }
}