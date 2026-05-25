<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'department', 'position'])
            ->latest()
            ->paginate(10);
        
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Auto-generate unique employee code
        $year = date('Y');
        $lastCode = Employee::withTrashed()
            ->where('employee_code', 'like', 'EMP' . $year . '%')
            ->orderBy('employee_code', 'desc')
            ->first();

        if ($lastCode && preg_match('/EMP' . $year . '(\d+)/', $lastCode->employee_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        do {
            $employeeCode = 'EMP' . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (Employee::withTrashed()->where('employee_code', $employeeCode)->exists());

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        // Assign Employee role
        $user->assignRole('Employee');

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
        }

        // Create employee record
        Employee::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'position_id' => $validated['position_id'],
            'employee_code' => $employeeCode,
            'joining_date' => $validated['joining_date'],
            'salary' => $validated['salary'],
            'employment_type' => $validated['employment_type'],
            'employment_status' => 'active',
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully. Code: ' . $employeeCode);
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'position', 'attendances' => function($query) {
            $query->latest()->take(30);
        }, 'leaveRequests' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'employment_status' => 'required|in:active,on_leave,terminated,resigned',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update user if exists
        if ($employee->user) {
            $employee->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            if ($request->hasFile('avatar')) {
                if ($employee->user->avatar) {
                    Storage::disk('public')->delete($employee->user->avatar);
                }
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $employee->user->update(['avatar' => $avatarPath]);
            }
        }

        $employee->update([
            'department_id' => $validated['department_id'],
            'position_id' => $validated['position_id'],
            'salary' => $validated['salary'],
            'employment_type' => $validated['employment_type'],
            'employment_status' => $validated['employment_status'],
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user) {
            if ($employee->user->avatar) {
                Storage::disk('public')->delete($employee->user->avatar);
            }
            $employee->user->delete();
        }
        
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function export()
    {
        $employees = Employee::with(['user', 'department', 'position'])->get();
        
        $filename = 'employees_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Employee Code', 'Department', 'Position', 'Salary', 'Status', 'Joining Date']);
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->user->name ?? 'Unknown',
                    $employee->user->email ?? 'N/A',
                    $employee->employee_code,
                    $employee->department->name ?? 'N/A',
                    $employee->position->name ?? 'N/A',
                    $employee->salary,
                    $employee->employment_status,
                    $employee->joining_date ? $employee->joining_date->format('Y-m-d') : 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}