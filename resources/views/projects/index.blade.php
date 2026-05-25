{{-- resources/views/projects/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Project Management</h2>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>New Project
        </a>
    </div>

    <div class="row">
        @forelse($projects as $project)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $project->name }}</h5>
                        <span class="badge bg-{{ 
                            $project->status == 'completed' ? 'success' : 
                            ($project->status == 'in_progress' ? 'primary' : 
                            ($project->status == 'on_hold' ? 'warning' : 'secondary')) 
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                    
                    <p class="text-muted small mb-2">{{ $project->code }}</p>
                    <p class="text-muted small">{{ Str::limit($project->description, 100) }}</p>
                    
                    <div class="mb-2">
                        <small class="text-muted">Progress</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $project->progress_percentage }}%"></div>
                        </div>
                        <small class="text-end d-block">{{ $project->progress_percentage }}%</small>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <small class="text-muted d-block">Tasks</small>
                            <strong>{{ $project->tasks_count }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Manager</small>
                            <strong>{{ $project->projectManager->name }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Budget</small>
                            <strong>${{ number_format($project->budget ?? 0) }}</strong>
                        </div>
                    </div>

                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-kanban me-2"></i>View Board
                    </a>
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $project->start_date->format('M d, Y') }}
                        @if($project->end_date)
                            - {{ $project->end_date->format('M d, Y') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-kanban" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No projects found</h4>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create First Project</a>
        </div>
        @endforelse
    </div>
    <div class="mt-3">{{ $projects->links() }}</div>
</div>
@endsection