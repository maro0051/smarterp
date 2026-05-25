<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance records.
     * HR/Admin sees all, Employee sees only their own.
     */
    public function index()
    {
        if (auth()->user()->can('view all attendance')) {
            // HR/Admin: Show all attendance records
            $attendances = Attendance::with('employee.user')
                ->latest('date')
                ->latest('time_in')
                ->paginate(15);
            
            $todayAttendance = Attendance::whereDate('date', Carbon::today())->count();
            $presentToday = Attendance::whereDate('date', Carbon::today())
                ->where('status', 'present')->count();
            $lateToday = Attendance::whereDate('date', Carbon::today())
                ->where('status', 'late')->count();
        } else {
            // Employee: Show only their own attendance
            $employee = Employee::where('user_id', auth()->id())->first();
            
            if ($employee) {
                $attendances = Attendance::with('employee.user')
                    ->where('employee_id', $employee->id)
                    ->latest('date')
                    ->latest('time_in')
                    ->paginate(15);
            } else {
                $attendances = collect();
            }
            
            $todayAttendance = 0;
            $presentToday = 0;
            $lateToday = 0;
        }
        
        return view('attendance.index', compact(
            'attendances', 
            'todayAttendance', 
            'presentToday', 
            'lateToday'
        ));
    }

    /**
     * Show the punch in/out page for employees
     */
    public function punch()
    {
        $today = Carbon::today();
        
        // Get today's attendance for the logged-in employee
        $employee = Employee::where('user_id', auth()->id())->first();
        $todayAttendance = null;
        $hasCheckedIn = false;
        $hasCheckedOut = false;
        
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();
            
            if ($todayAttendance) {
                $hasCheckedIn = true;
                if ($todayAttendance->time_out) {
                    $hasCheckedOut = true;
                }
            }
        }
        
        return view('attendance.punch', compact('todayAttendance', 'hasCheckedIn', 'hasCheckedOut', 'today'));
    }

    /**
     * Employee self punch-in using employee code
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string|exists:employees,employee_code',
        ]);

        $employee = Employee::where('employee_code', $request->employee_code)
            ->where('employment_status', 'active')
            ->first();

        if (!$employee) {
            return back()->with('error', 'Invalid or inactive employee code.');
        }

        $today = Carbon::today();
        $now = Carbon::now();
        
        // Check if already checked in today
        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', $employee->user->name . ' has already checked in today at ' . Carbon::parse($existing->time_in)->format('h:i A') . '.');
        }

        // Determine if late (after 9:00 AM)
        $lateThreshold = Carbon::today()->setHour(9)->setMinute(0)->setSecond(0);
        $status = $now->gt($lateThreshold) ? 'late' : 'present';

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'time_in' => $now,
            'status' => $status,
        ]);

        $message = 'Check-in recorded at ' . $now->format('h:i A') . '.';
        if ($status == 'late') {
            $message .= ' (Late)';
        }

        return back()->with('success', $employee->user->name . ' - ' . $message);
    }

    /**
     * Employee self punch-out using employee code
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string|exists:employees,employee_code',
        ]);

        $employee = Employee::where('employee_code', $request->employee_code)
            ->where('employment_status', 'active')
            ->first();

        if (!$employee) {
            return back()->with('error', 'Invalid or inactive employee code.');
        }

        $today = Carbon::today();
        $now = Carbon::now();
        
        // Find today's attendance record
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', $employee->user->name . ' has not checked in today. Please check in first.');
        }

        if ($attendance->time_out) {
            return back()->with('error', $employee->user->name . ' has already checked out today at ' . Carbon::parse($attendance->time_out)->format('h:i A') . '.');
        }

        $attendance->update([
            'time_out' => $now,
        ]);

        return back()->with('success', $employee->user->name . ' - Check-out recorded at ' . $now->format('h:i A') . '. Have a great day!');
    }

    /**
     * HR/Admin manually records attendance for an employee
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required',
            'time_out' => 'nullable',
            'status' => 'required|in:present,late,absent,half_day',
            'remarks' => 'nullable|string',
        ]);

        // Check if attendance already exists for this employee on this date
        $existing = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existing) {
            // Update existing record
            $existing->update([
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            $message = 'Attendance updated successfully.';
        } else {
            // Create new record
            Attendance::create([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            $message = 'Attendance recorded successfully.';
        }

        return back()->with('success', $message);
    }

    /**
     * Show the form for editing attendance (HR/Admin only)
     */
    public function edit(Attendance $attendance)
    {
        if (!auth()->user()->can('view all attendance')) {
            abort(403);
        }

        $employees = Employee::with('user')
            ->where('employment_status', 'active')
            ->get();
        
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    /**
     * Update attendance record (HR/Admin only)
     */
    public function update(Request $request, Attendance $attendance)
    {
        if (!auth()->user()->can('view all attendance')) {
            abort(403);
        }

        $request->validate([
            'time_in' => 'required',
            'time_out' => 'nullable',
            'status' => 'required|in:present,late,absent,half_day',
            'remarks' => 'nullable|string',
        ]);

        $attendance->update([
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Delete attendance record (HR/Admin only)
     */
    public function destroy(Attendance $attendance)
    {
        if (!auth()->user()->can('view all attendance')) {
            abort(403);
        }

        $attendance->delete();

        return back()->with('success', 'Attendance record deleted.');
    }
}