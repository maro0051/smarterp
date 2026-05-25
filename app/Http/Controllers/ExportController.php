<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function employees()
    {
        $employees = Employee::with(['user', 'department', 'position'])->get();
        
        $filename = 'employees_' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
        
        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Code', 'Department', 'Position', 'Salary', 'Status', 'Joining Date']);
            foreach ($employees as $emp) {
                fputcsv($file, [
                    $emp->user->name, $emp->user->email, $emp->employee_code,
                    $emp->department->name, $emp->position->name, $emp->salary,
                    $emp->employment_status, $emp->joining_date->format('Y-m-d')
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function customers()
    {
        $customers = Customer::all();
        
        $filename = 'customers_' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
        
        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Company', 'Address', 'Status']);
            foreach ($customers as $cust) {
                fputcsv($file, [$cust->name, $cust->email, $cust->phone, $cust->company_name, $cust->address, $cust->status]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}