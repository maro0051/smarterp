@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">HR Dashboard 👥</h4>
                            <p class="mb-0 opacity-75">{{ now()->format('l, F d, Y') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('employees.create') }}" class="btn btn-light me-2">
                                <i class="bi bi-person-plus me-1"></i> Add Employee
                            </a>
                            <a href="{{ route('hr.recruitments.create') }}" class="btn btn-outline-light">
                                <i class="bi bi-briefcase me-1"></i> Post Job
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <a href="{{ route('employees.index') }}" class="text-decoration-none">
                <div class="stat-card blue" style="cursor: pointer;">
                    <div class="icon"><i class="bi bi-people-fill"></i></div>
                    <p>Total Employees</p>
                    <h3>{{ $totalEmployees }}</h3>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <a href="{{ route('departments.index') }}" class="text-decoration-none">
                <div class="stat-card green" style="cursor: pointer;">
                    <div class="icon"><i class="bi bi-diagram-3"></i></div>
                    <p>Departments</p>
                    <h3>{{ $totalDepartments }}</h3>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card orange">
                <div class="icon"><i class="bi bi-clock"></i></div>
                <p>On Leave Today</p>
                <h3>{{ $onLeaveToday }}</h3>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card purple">
                <div class="icon"><i class="bi bi-person-check"></i></div>
                <p>New Hires (Month)</p>
                <h3>{{ $newHiresThisMonth }}</h3>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <a href="{{ route('leave-requests.index') }}" class="text-decoration-none">
                <div class="stat-card" style="cursor: pointer; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="icon"><i class="bi bi-calendar-x"></i></div>
                    <p>Pending Leaves</p>
                    <h3>{{ $pendingLeaveCount }}</h3>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <a href="{{ route('hr.recruitments.index') }}" class="text-decoration-none">
                <div class="stat-card" style="cursor: pointer; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <div class="icon"><i class="bi bi-briefcase"></i></div>
                    <p>Open Positions</p>
                    <h3>{{ $openRecruitments }}</h3>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Today's Attendance --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Today's Attendance</h5>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                <i class="bi bi-check-circle text-success fs-1"></i>
                                <h3 class="text-success mb-0">{{ $presentToday }}</h3>
                                <small class="text-muted">Present</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                                <i class="bi bi-x-circle text-danger fs-1"></i>
                                <h3 class="text-danger mb-0">{{ max(0, $absentToday) }}</h3>
                                <small class="text-muted">Absent</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 8px;">
                            @php $attendanceRate = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0; @endphp
                            <div class="progress-bar bg-success" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                        <small class="text-muted">{{ $attendanceRate }}% attendance rate</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Leave Requests --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pending Leave Requests</h5>
                    <span class="badge bg-warning">{{ $pendingLeaveCount }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($pendingLeaves as $leave)
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $leave->employee->user->name ?? 'Unknown' }}</strong>
                                <br>
                                <small class="text-muted">{{ $leave->leaveType->name ?? 'N/A' }}</small>
                                <br>
                                <small>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }} ({{ $leave->total_days }} days)</small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <form action="{{ route('leave-requests.approve', $leave->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <form action="{{ route('leave-requests.reject', $leave->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" title="Reject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 32px;"></i>
                        <p class="mt-2">No pending leave requests</p>
                    </div>
                    @endforelse
                </div>
                @if($pendingLeaveCount > 5)
                <div class="card-footer text-center py-2">
                    <a href="{{ route('leave-requests.index') }}" class="text-decoration-none">
                        View all {{ $pendingLeaveCount }} requests →
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Upcoming Interviews --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Interviews</h5>
                    <a href="{{ route('hr.recruitments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @forelse($upcomingInterviews as $interview)
                    <div class="p-3 border-bottom">
                        <strong>{{ $interview->candidate_name }}</strong>
                        <br>
                        <small class="text-muted">{{ $interview->recruitment->position ?? 'N/A' }}</small>
                        <br>
                        <small>
                            <i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($interview->interview_date)->format('M d, Y h:i A') }}
                        </small>
                        <br>
                        <small class="text-muted">Interviewer: {{ $interview->interviewer->name ?? 'N/A' }}</small>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar" style="font-size: 32px;"></i>
                        <p class="mt-2">No upcoming interviews</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        {{-- Attendance Trend Chart --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Attendance Trend (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceTrendChart" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Department Distribution Chart --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Employees by Department</h5>
                </div>
                <div class="card-body">
                    <canvas id="deptDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        {{-- Recent Hires --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Hires</h5>
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentHires as $hire)
                                <tr>
                                    <td>
                                        <a href="{{ route('employees.show', $hire->id) }}">
                                            {{ $hire->user->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>{{ $hire->department->name ?? 'N/A' }}</td>
                                    <td>{{ $hire->position->name ?? 'N/A' }}</td>
                                    <td>{{ $hire->joining_date->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No recent hires</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent HR Activities --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent HR Activities</h5>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>When</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->user->name ?? 'System' }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td><small>{{ $activity->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No recent activities</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Attendance Trend Chart
    const trendCtx = document.getElementById('attendanceTrendChart')?.getContext('2d');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendMonths ?? []) !!},
                datasets: [{
                    label: 'Attendance Count',
                    data: {!! json_encode($attendanceTrend ?? []) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // Department Distribution Chart
    const deptCtx = document.getElementById('deptDistributionChart')?.getContext('2d');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($deptDistribution->pluck('name') ?? []) !!},
                datasets: [{
                    data: {!! json_encode($deptDistribution->pluck('total') ?? []) !!},
                    backgroundColor: [
                        '#3b82f6', '#f97316', '#10b981', '#8b5cf6', 
                        '#f59e0b', '#ec4899', '#06b6d4'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'right',
                        labels: { padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection