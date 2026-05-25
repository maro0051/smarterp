<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests.
     * HR/Admin sees all, Employee sees only their own.
     */
    public function index()
    {
        if (auth()->user()->can('view all leave requests')) {
            // HR/Admin: Show all leave requests
            $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType', 'approvedBy'])
                ->latest()
                ->paginate(10);
        } else {
            // Employee: Show only their own leave requests
            $employee = Employee::where('user_id', auth()->id())->first();
            
            if ($employee) {
                $leaveRequests = LeaveRequest::with(['leaveType', 'approvedBy'])
                    ->where('employee_id', $employee->id)
                    ->latest()
                    ->paginate(10);
            } else {
                $leaveRequests = collect();
            }
        }
        
        $pendingCount = LeaveRequest::where('status', 'pending')->count();
        $approvedCount = LeaveRequest::where('status', 'approved')
            ->whereYear('created_at', date('Y'))
            ->count();
        
        return view('leave-requests.index', compact('leaveRequests', 'pendingCount', 'approvedCount'));
    }

    /**
     * Show the form for creating a new leave request.
     * Employee auto-selects themselves, HR can select any employee.
     */
    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        // Get the current user's employee record (for employee self-service)
        $employee = Employee::where('user_id', auth()->id())->first();
        
        // Get all employees (for HR to select)
        $employees = Employee::with('user')
            ->where('employment_status', 'active')
            ->get();
        
        return view('leave-requests.create', compact('leaveTypes', 'employee', 'employees'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        // If employee creating for themselves, auto-set their employee ID
        if (!auth()->user()->can('view all leave requests')) {
            $employee = Employee::where('user_id', auth()->id())->first();
            if (!$employee) {
                return back()->with('error', 'Employee record not found. Please contact HR.');
            }
            $request->merge(['employee_id' => $employee->id]);
        }
        
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Check if employee has enough leave days remaining
        $leaveType = LeaveType::find($validated['leave_type_id']);
        $usedDays = LeaveRequest::where('employee_id', $validated['employee_id'])
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('status', 'approved')
            ->whereYear('start_date', date('Y'))
            ->sum('total_days');
        
        if (($usedDays + $totalDays) > $leaveType->days_per_year) {
            return back()->with('error', 'You have exceeded the maximum ' . $leaveType->days_per_year . ' days for ' . $leaveType->name . '. Used: ' . $usedDays . ' days.');
        }

        // Check for overlapping leave requests
        $overlapping = LeaveRequest::where('employee_id', $validated['employee_id'])
            ->where('status', '!=', 'rejected')
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();
        
        if ($overlapping) {
            return back()->with('error', 'You already have a leave request for these dates.');
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Notify HR Managers about new leave request
        $hrUsers = \App\Models\User::role(['Super Admin', 'HR Manager'])->get();
        foreach ($hrUsers as $hrUser) {
            Notification::create([
                'user_id' => $hrUser->id,
                'title' => 'New Leave Request',
                'message' => auth()->user()->name . ' has submitted a ' . $totalDays . '-day ' . $leaveType->name . ' request.',
                'type' => 'info',
                'icon' => 'bi bi-calendar-x',
            ]);
        }

        $message = 'Leave request submitted successfully.';
        if (auth()->user()->can('view all leave requests')) {
            $message .= ' HR will review it.';
        }

        return redirect()->route('leave-requests.index')
            ->with('success', $message);
    }

    /**
     * Approve a leave request. (HR/Admin only)
     */
    public function approve(LeaveRequest $leaveRequest)
    {
        if (!auth()->user()->can('approve leave requests')) {
            abort(403, 'You do not have permission to approve leave requests.');
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Notify the employee
        $employee = Employee::find($leaveRequest->employee_id);
        if ($employee) {
            Notification::create([
                'user_id' => $employee->user_id,
                'title' => 'Leave Approved ✅',
                'message' => 'Your ' . $leaveRequest->total_days . '-day leave from ' . $leaveRequest->start_date->format('M d') . ' to ' . $leaveRequest->end_date->format('M d') . ' has been approved.',
                'type' => 'success',
                'icon' => 'bi bi-check-circle',
            ]);
        }

        return back()->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request. (HR/Admin only)
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        if (!auth()->user()->can('reject leave requests')) {
            abort(403, 'You do not have permission to reject leave requests.');
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->input('reason', 'No reason provided'),
        ]);

        // Notify the employee
        $employee = Employee::find($leaveRequest->employee_id);
        if ($employee) {
            $reasonText = $leaveRequest->rejection_reason ? ' Reason: ' . $leaveRequest->rejection_reason : '';
            Notification::create([
                'user_id' => $employee->user_id,
                'title' => 'Leave Rejected ❌',
                'message' => 'Your leave request from ' . $leaveRequest->start_date->format('M d') . ' to ' . $leaveRequest->end_date->format('M d') . ' has been rejected.' . $reasonText,
                'type' => 'danger',
                'icon' => 'bi bi-x-circle',
            ]);
        }

        return back()->with('success', 'Leave request rejected.');
    }

    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // Check if user can view this leave request
        if (!auth()->user()->can('view all leave requests')) {
            $employee = Employee::where('user_id', auth()->id())->first();
            if (!$employee || $leaveRequest->employee_id !== $employee->id) {
                abort(403, 'You can only view your own leave requests.');
            }
        }

        $leaveRequest->load(['employee.user', 'leaveType', 'approvedBy']);
        
        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Remove the specified leave request. (Only pending requests can be deleted)
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        // Only allow deletion of own pending requests, or all if HR/Admin
        if (!auth()->user()->can('view all leave requests')) {
            $employee = Employee::where('user_id', auth()->id())->first();
            if (!$employee || $leaveRequest->employee_id !== $employee->id) {
                abort(403);
            }
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be deleted.');
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request deleted successfully.');
    }
}