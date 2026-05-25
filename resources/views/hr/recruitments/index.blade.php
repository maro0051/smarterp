@extends('layouts.app')

@section('title', 'Recruitment')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Job Postings</h2>
        <a href="{{ route('hr.recruitments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Post New Job
        </a>
    </div>

    <div class="row">
        @forelse($recruitments as $job)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5>{{ $job->position }}</h5>
                            <p class="text-muted mb-1">{{ $job->department->name }}</p>
                        </div>
                        <span class="badge bg-{{ $job->status == 'open' ? 'success' : ($job->status == 'closed' ? 'danger' : 'warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <p class="mt-2">{{ Str::limit($job->description, 150) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Deadline: {{ $job->deadline->format('M d, Y') }}</small>
                        <small class="text-muted">{{ $job->number_of_positions }} position(s)</small>
                    </div>
                    <a href="{{ route('hr.recruitments.show', $job->id) }}" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-briefcase" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No job postings</h4>
            <a href="{{ route('hr.recruitments.create') }}" class="btn btn-primary">Post First Job</a>
        </div>
        @endforelse
    </div>
    {{ $recruitments->links() }}
</div>
@endsection