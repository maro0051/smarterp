@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            @can('view all attendance')
                Attendance Management
            @else
                My Attendance
            @endcan
        </h2>
        <div>
            <a href="{{ route('attendance.punch') }}" class="btn btn-success me-2">
                <i class="bi bi-clock me-2"></i>Punch In/Out
            </a>
            @can('view all attendance')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordAttendanceModal">
                <i class="bi bi-plus-lg me-2"></i>Record Attendance
            </button>
            @endcan
        </div>
    </div>

    @can('view all attendance')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card green">
                <div class="icon"><i class="bi bi-check-circle"></i></div>
                <p>Present Today</p>
                <h3>{{ $presentToday ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card orange">
                <div class="icon"><i class="bi bi-clock-history"></i></div>
                <p>Late Today</p>
                <h3>{{ $lateToday ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card blue">
                <div class="icon"><i class="bi bi-people"></i></div>
                <p>Total Today</p>
                <h3>{{ $todayAttendance ?? 0 }}</h3>
            </div>
        </div>
    </div>
    @endcan

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @can('view all attendance')
                            <th>Employee</th>
                            @endcan
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Status</th>
                            @can('view all attendance')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            @can('view all attendance')
                            <td>
                                @if($attendance->employee && $attendance->employee->user)
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px; font-size: 14px;">
                                            {{ strtoupper(substr($attendance->employee->user->name, 0, 1)) }}
                                        </div>
                                        {{ $attendance->employee->user->name }}
                                    </div>
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            @endcan
                            <td>{{ $attendance->date ? $attendance->date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '-' }}</td>
                            <td>{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    ($attendance->status ?? '') == 'present' ? 'success' : 
                                    (($attendance->status ?? '') == 'late' ? 'warning' : 
                                    (($attendance->status ?? '') == 'absent' ? 'danger' : 'info')) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status ?? 'N/A')) }}
                                </span>
                            </td>
                            @can('view all attendance')
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(this.form)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('view all attendance') ? '6' : '4' }}" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-check" style="font-size: 48px;"></i>
                                <p class="mt-2">No attendance records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $attendances->links() }}</div>
</div>

@can('view all attendance')
<div class="modal fade" id="recordAttendanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Employee *</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach(\App\Models\Employee::with('user')->where('employment_status', 'active')->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->user->name ?? 'Unknown' }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time In *</label>
                        <input type="time" name="time_in" class="form-control" value="09:00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time Out</label>
                        <input type="time" name="time_out" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                            <option value="half_day">Half Day</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection