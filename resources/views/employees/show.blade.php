@extends('layouts.app')

@section('title', $employee->user->name ?? 'Employee')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Employee Profile</h2>
        <div>
            @can('edit employees')
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            @endcan
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(!$employee->user)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>
            This employee record has no associated user account. It may be corrupted.
            <a href="{{ route('employees.index') }}" class="alert-link">Return to employee list</a>
        </div>
    @else
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($employee->user->avatar)
                        <img src="{{ asset('storage/' . $employee->user->avatar) }}" 
                             class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px; font-size: 64px;">
                            {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <h4>{{ $employee->user->name }}</h4>
                    <p class="text-muted">{{ $employee->position->name ?? 'N/A' }}</p>
                    
                    <span class="badge bg-{{ ($employee->employment_status ?? '') == 'active' ? 'success' : (($employee->employment_status ?? '') == 'on_leave' ? 'warning' : 'danger') }} mb-3">
                        {{ ucfirst(str_replace('_', ' ', $employee->employment_status ?? 'N/A')) }}
                    </span>

                    <hr>

                    <div class="text-start">
                        <p><i class="bi bi-envelope me-2"></i>{{ $employee->user->email }}</p>
                        <p><i class="bi bi-telephone me-2"></i>{{ $employee->user->phone ?? 'N/A' }}</p>
                        <p><i class="bi bi-calendar me-2"></i>{{ $employee->user->date_of_birth ? $employee->user->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                        <p><i class="bi bi-gender-ambiguous me-2"></i>{{ ucfirst($employee->user->gender ?? 'N/A') }}</p>
                        <p><i class="bi bi-geo-alt me-2"></i>{{ $employee->user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Employment Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Employee Code:</strong> {{ $employee->employee_code }}</p>
                    <p><strong>Department:</strong> {{ $employee->department->name ?? 'N/A' }}</p>
                    <p><strong>Position:</strong> {{ $employee->position->name ?? 'N/A' }}</p>
                    <p><strong>Joining Date:</strong> {{ $employee->joining_date ? $employee->joining_date->format('M d, Y') : 'N/A' }}</p>
                    <p><strong>Employment Type:</strong> {{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? 'N/A')) }}</p>
                    <p><strong>Salary:</strong> ${{ number_format($employee->salary ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Attendance History -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Recent Attendance</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->attendances ?? [] as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                                    <td>{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($attendance->status ?? '') == 'present' ? 'success' : (($attendance->status ?? '') == 'late' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status ?? 'N/A')) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No attendance records</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Leave History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Requests</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->leaveRequests ?? [] as $leave)
                                <tr>
                                    <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                    <td>{{ $leave->start_date ? $leave->start_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $leave->end_date ? $leave->end_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $leave->total_days ?? 0 }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($leave->status ?? '') == 'approved' ? 'success' : (($leave->status ?? '') == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($leave->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No leave requests</td>
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