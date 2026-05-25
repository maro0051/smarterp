{{-- resources/views/activity-logs/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Activity Logs</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $log->action }}</span>
                            </td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->description ?? 'N/A' }}</td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-clock-history" style="font-size: 48px;"></i>
                                <p class="mt-2">No activity logs found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection