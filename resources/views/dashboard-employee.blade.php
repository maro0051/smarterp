@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Welcome Message --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h4>
                            <p class="mb-0 opacity-75">{{ now()->format('l, F d, Y') }}</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('attendance.punch') }}" class="btn btn-light me-2">
                                <i class="bi bi-clock me-1"></i> Punch In/Out
                            </a>
                            <a href="{{ route('leave-requests.create') }}" class="btn btn-outline-light">
                                <i class="bi bi-calendar-x me-1"></i> Request Leave
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$employee)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Your employee record is not set up yet. Please contact HR.
        </div>
    @else
    {{-- Today's Status --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="display-4 mb-2">
                        @if($todayAttendance)
                            @if($todayAttendance->time_out)
                                <i class="bi bi-check-circle text-success"></i>
                            @else
                                <i class="bi bi-clock text-warning"></i>
                            @endif
                        @else
                            <i class="bi bi-x-circle text-danger"></i>
                        @endif
                    </div>
                    <h5>Today's Attendance</h5>
                    @if($todayAttendance)
                        <p class="mb-1">
                            <strong>In:</strong> {{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }}
                            @if($todayAttendance->time_out)
                                | <strong>Out:</strong> {{ \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') }}
                            @else
                                <br><span class="badge bg-warning">Still checked in</span>
                            @endif
                        </p>
                        <span class="badge bg-{{ $todayAttendance->status == 'present' ? 'success' : 'warning' }}">
                            {{ ucfirst($todayAttendance->status) }}
                        </span>
                    @else
                        <p class="text-muted">Not checked in yet</p>
                        <a href="{{ route('attendance.punch') }}" class="btn btn-primary btn-sm">Punch In Now</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="display-4 mb-2">
                        <i class="bi bi-calendar-check text-primary"></i>
                    </div>
                    <h5>This Month's Attendance</h5>
                    <div class="row mt-2">
                        <div class="col-4">
                            <span class="badge bg-success d-block">Present</span>
                            <strong>{{ $monthStats['present'] }}</strong>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-warning d-block">Late</span>
                            <strong>{{ $monthStats['late'] }}</strong>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-danger d-block">Absent</span>
                            <strong>{{ $monthStats['absent'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="display-4 mb-2">
                        <i class="bi bi-cash-stack text-success"></i>
                    </div>
                    <h5>Latest Payslip</h5>
                    @if($latestPayslip)
                        <h3 class="text-success">${{ number_format($latestPayslip->net_salary, 2) }}</h3>
                        <p class="text-muted small mb-1">{{ $latestPayslip->payroll->period_start->format('M Y') }}</p>
                        <a href="{{ route('payslips.my') }}" class="btn btn-outline-success btn-sm">View All</a>
                    @else
                        <p class="text-muted">No payslips yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Leave Balance & Upcoming --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leave Balance ({{ date('Y') }})</h5>
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-sm btn-primary">Request Leave</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Used</th>
                                    <th>Remaining</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveBalance as $balance)
                                <tr>
                                    <td>{{ $balance['type'] }}</td>
                                    <td>{{ $balance['used'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $balance['remaining'] > 0 ? 'success' : 'danger' }}">
                                            {{ $balance['remaining'] }}
                                        </span>
                                    </td>
                                    <td>{{ $balance['total'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Leave</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($upcomingLeaves as $leave)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $leave->leaveType->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                        ({{ $leave->total_days }} days)
                                    </small>
                                </div>
                                <span class="badge bg-success">Approved</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-check" style="font-size: 32px;"></i>
                            <p class="mt-2">No upcoming leave</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Tasks & Recent Attendance --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Tasks</h5>
                    <span class="badge bg-primary">{{ $pendingTasks->count() }} pending</span>
                </div>
                <div class="card-body p-0">
                    @forelse($pendingTasks as $task)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $task->title }}</strong>
                                    <br>
                                    <small class="text-muted">Project: {{ $task->project->name ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-{{ 
                                    $task->priority == 'urgent' ? 'danger' : 
                                    ($task->priority == 'high' ? 'warning' : 
                                    ($task->priority == 'medium' ? 'info' : 'secondary')) 
                                }}">{{ ucfirst($task->priority) }}</span>
                            </div>
                            @if($task->due_date)
                                <small class="text-muted">Due: {{ $task->due_date->format('M d, Y') }}</small>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle" style="font-size: 32px;"></i>
                            <p class="mt-2">No pending tasks</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Attendance</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendance as $att)
                                <tr>
                                    <td>{{ $att->date->format('M d') }}</td>
                                    <td>{{ $att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('h:i A') : '-' }}</td>
                                    <td>{{ $att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('h:i A') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No records</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection