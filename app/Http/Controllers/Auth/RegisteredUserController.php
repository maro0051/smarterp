<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        return view('auth.register', compact('roles', 'departments', 'positions'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'phone' => ['nullable', 'string'],
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
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Create employee record
        Employee::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'employee_code' => $employeeCode,
            'joining_date' => now(),
            'salary' => $request->salary ?? 0,
            'employment_type' => 'full_time',
            'employment_status' => 'active',
        ]);

        event(new Registered($user));

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully! Name: ' . $request->name . ', Code: ' . $employeeCode . ', Role: ' . $request->role);
    }
}