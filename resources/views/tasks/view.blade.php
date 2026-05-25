@extends('layouts.app')

@section('title', 'Task: ' . $task->title)

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('tasks.my') }}" class="btn btn-sm btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                                <h3 class="mb-0">{{ $task->title }}</h3>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center ms-5">
                                <span class="badge bg-{{ 
                                    $task->priority == 'urgent' ? 'danger' : 
                                    ($task->priority == 'high' ? 'warning' : 
                                    ($task->priority == 'medium' ? 'info' : 'secondary')) 
                                }}">{{ ucfirst($task->priority) }} Priority</span>
                                <span class="badge bg-{{ 
                                    $task->status == 'completed' ? 'success' : 
                                    ($task->status == 'in_progress' ? 'primary' : 
                                    ($task->status == 'review' ? 'warning' : 'secondary')) 
                                }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                @if($task->due_date)
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-calendar me-1"></i> Due: {{ $task->due_date->format('M d, Y') }}
                                        @if($task->due_date->isPast() && $task->status != 'completed')
                                            <span class="text-danger">(Overdue!)</span>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            @if($task->project)
                                <a href="{{ route('projects.show', $task->project_id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-kanban me-1"></i> View Board
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Description --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Description</h5>
                </div>
                <div class="card-body">
                    @if($task->description)
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $task->description }}</p>
                    @else
                        <p class="text-muted mb-0">No description provided.</p>
                    @endif
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Progress</h5>
                </div>
                <div class="card-body">
                    <div class="task-timeline">
                        <div class="d-flex align-items-center mb-3">
                            <div class="timeline-icon {{ in_array($task->status, ['todo','in_progress','review','completed']) ? 'bg-success' : 'bg-secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <div>
                                <strong>Task Created</strong>
                                <br><small class="text-muted">{{ $task->created_at->format('M d, Y h:i A') }}</small>
                                <br><small>By: {{ $task->createdBy->name ?? 'Unknown' }}</small>
                            </div>
                        </div>
                        
                        @if(in_array($task->status, ['in_progress','review','completed']))
                        <div class="d-flex align-items-center mb-3">
                            <div class="timeline-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-play-fill"></i>
                            </div>
                            <div>
                                <strong>In Progress</strong>
                                <br><small class="text-muted">Working on this task</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($task->status, ['review','completed']))
                        <div class="d-flex align-items-center mb-3">
                            <div class="timeline-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-eye"></i>
                            </div>
                            <div>
                                <strong>Submitted for Review</strong>
                                <br><small class="text-muted">Waiting for approval</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->status == 'completed')
                        <div class="d-flex align-items-center">
                            <div class="timeline-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <strong>Completed</strong>
                                <br><small class="text-muted">{{ $task->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            @if($task->assigned_to == auth()->id())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @if($task->status == 'todo')
                            <button class="btn btn-primary" onclick="updateTaskStatus({{ $task->id }}, 'in_progress')">
                                <i class="bi bi-play-fill me-1"></i> Start Working
                            </button>
                        @endif
                        
                        @if($task->status == 'in_progress')
                            <button class="btn btn-warning" onclick="updateTaskStatus({{ $task->id }}, 'review')">
                                <i class="bi bi-send me-1"></i> Submit for Review
                            </button>
                            <button class="btn btn-success" onclick="updateTaskStatus({{ $task->id }}, 'completed')">
                                <i class="bi bi-check-lg me-1"></i> Mark as Complete
                            </button>
                        @endif
                        
                        @if($task->status == 'review')
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                This task is under review. You'll be notified when it's approved.
                            </div>
                        @endif
                        
                        @if($task->status == 'completed')
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                Great job! This task has been completed.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Task Info --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Project</small>
                        <strong>
                            @if($task->project)
                                <a href="{{ route('projects.show', $task->project_id) }}">{{ $task->project->name }}</a>
                            @else
                                N/A
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Assigned To</small>
                        <strong>
                            @if($task->assignedTo)
                                <div class="d-flex align-items-center mt-1">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 30px; height: 30px; font-size: 12px;">
                                        {{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}
                                    </div>
                                    {{ $task->assignedTo->name }}
                                </div>
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Created By</small>
                        <strong>{{ $task->createdBy->name ?? 'Unknown' }}</strong>
                    </div>
                    @if($task->project && $task->project->projectManager)
                    <div class="mb-3">
                        <small class="text-muted d-block">Project Manager</small>
                        <strong>{{ $task->project->projectManager->name }}</strong>
                    </div>
                    @endif
                    <div class="mb-3">
                        <small class="text-muted d-block">Priority</small>
                        <span class="badge bg-{{ 
                            $task->priority == 'urgent' ? 'danger' : 
                            ($task->priority == 'high' ? 'warning' : 
                            ($task->priority == 'medium' ? 'info' : 'secondary')) 
                        }}">{{ ucfirst($task->priority) }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge bg-{{ 
                            $task->status == 'completed' ? 'success' : 
                            ($task->status == 'in_progress' ? 'primary' : 
                            ($task->status == 'review' ? 'warning' : 'secondary')) 
                        }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Due Date</small>
                        <strong class="{{ $task->due_date && $task->due_date->isPast() && $task->status != 'completed' ? 'text-danger' : '' }}">
                            {{ $task->due_date ? $task->due_date->format('F d, Y') : 'No due date' }}
                            @if($task->due_date && $task->due_date->isPast() && $task->status != 'completed')
                                <i class="bi bi-exclamation-triangle text-danger"></i>
                            @endif
                        </strong>
                    </div>
                </div>
            </div>

            {{-- Project Progress --}}
            @if($task->project)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Project Progress</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary mb-0">{{ $task->project->progress_percentage }}%</h2>
                    <div class="progress mt-2" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar bg-primary" style="width: {{ $task->project->progress_percentage }}%; border-radius: 10px;"></div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        {{ $task->project->tasks()->where('status', 'completed')->count() }}/{{ $task->project->tasks()->count() }} tasks completed
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateTaskStatus(taskId, status) {
    const messages = {
        'in_progress': 'Task started! 🚀',
        'review': 'Task submitted for review! 📤',
        'completed': 'Task completed! ✅'
    };
    
    fetch(`/tasks/${taskId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(messages[status] || 'Task updated!', 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            showToast(data.message || 'Error', 'error');
        }
    });
}
</script>
@endpush
@endsection