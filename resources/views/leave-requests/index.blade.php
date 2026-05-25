@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            @can('view all leave requests')
                Leave Requests
            @else
                My Leave Requests
            @endcan
        </h2>
        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>New Request
        </a>
    </div>

    @can('view all leave requests')
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card orange">
                <p>Pending Requests</p>
                <h3>{{ $pendingCount }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card green">
                <p>Approved This Year</p>
                <h3>{{ $approvedCount }}</h3>
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
                            @can('view all leave requests')
                            <th>Employee</th>
                            @endcan
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            @can('approve leave requests')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leave)
                        <tr>
                            @can('view all leave requests')
                            <td>{{ $leave->employee->user->name }}</td>
                            @endcan
                            <td>{{ $leave->leaveType->name }}</td>
                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($leave->reason, 30) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $leave->status == 'approved' ? 'success' : 
                                    ($leave->status == 'pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            @can('approve leave requests')
                            <td>
                                @if($leave->status == 'pending')
                                <div class="btn-group">
                                    <form action="{{ route('leave-requests.approve', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('leave-requests.reject', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x" style="font-size: 48px;"></i>
                                <p class="mt-2">No leave requests found</p>
                                <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">Create Leave Request</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $leaveRequests->links() }}</div>
</div>
@endsection