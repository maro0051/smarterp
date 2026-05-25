@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="container-fluid">
    {{-- Project Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                                    <i class="bi bi-kanban text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1">{{ $project->name }}</h3>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge bg-secondary">{{ $project->code }}</span>
                                        <span class="badge bg-{{ 
                                            $project->status == 'completed' ? 'success' : 
                                            ($project->status == 'in_progress' ? 'primary' : 
                                            ($project->status == 'on_hold' ? 'warning' : 'info')) 
                                        }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $project->projectManager->name ?? 'Unassigned' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                    <i class="bi bi-plus-lg me-1"></i> Add Task
                                </button>
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Project Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <div class="text-primary mb-2"><i class="bi bi-list-check fs-1"></i></div>
                    <h4 class="mb-0">{{ $project->tasks->count() }}</h4>
                    <small class="text-muted">Total Tasks</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <div class="text-success mb-2"><i class="bi bi-check-circle fs-1"></i></div>
                    <h4 class="mb-0">{{ $project->tasks->where('status', 'completed')->count() }}</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <div class="text-warning mb-2"><i class="bi bi-arrow-repeat fs-1"></i></div>
                    <h4 class="mb-0">{{ $project->tasks->where('status', 'in_progress')->count() }}</h4>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <div class="text-info mb-2"><i class="bi bi-eye fs-1"></i></div>
                    <h4 class="mb-0">{{ $project->tasks->where('status', 'review')->count() }}</h4>
                    <small class="text-muted">In Review</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <div class="text-secondary mb-2"><i class="bi bi-inbox fs-1"></i></div>
                    <h4 class="mb-0">{{ $project->tasks->where('status', 'todo')->count() }}</h4>
                    <small class="text-muted">To Do</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <div class="mb-2">
                        <div class="progress-circle mx-auto" data-progress="{{ $project->progress_percentage }}">
                            <span class="progress-circle-value">{{ $project->progress_percentage }}%</span>
                        </div>
                    </div>
                    <small class="text-muted">Progress</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Project Progress</span>
                        <span class="text-primary fw-bold">{{ $project->progress_percentage }}%</span>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             style="width: {{ $project->progress_percentage }}%; border-radius: 10px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="row g-3 kanban-board">
        {{-- To Do Column --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0">
                        <span class="badge bg-secondary me-2">{{ $todoTasks->count() }}</span>
                        To Do
                    </h6>
                    <i class="bi bi-inbox text-secondary"></i>
                </div>
                <div class="card-body p-2" style="min-height: 300px; background: #f8fafc;">
                    @forelse($todoTasks as $task)
                    <div class="task-card mb-2" draggable="true" data-task-id="{{ $task->id }}">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 fw-bold">{{ $task->title }}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'in_progress')"><i class="bi bi-play me-2"></i>Start</button></li>
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'review')"><i class="bi bi-eye me-2"></i>Review</button></li>
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'completed')"><i class="bi bi-check-lg me-2"></i>Complete</button></li>
                                        </ul>
                                    </div>
                                </div>
                                @if($task->description)
                                <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ 
                                        $task->priority == 'urgent' ? 'danger' : 
                                        ($task->priority == 'high' ? 'warning' : 
                                        ($task->priority == 'medium' ? 'info' : 'secondary')) 
                                    }}">{{ ucfirst($task->priority) }}</span>
                                    @if($task->assignedTo)
                                        <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 28px; height: 28px; font-size: 11px;" 
                                             title="{{ $task->assignedTo->name }}">
                                            {{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                @if($task->due_date)
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-calendar me-1"></i>{{ $task->due_date->format('M d') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 32px;"></i>
                        <p class="mt-2 small">No tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- In Progress Column --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary bg-opacity-10 border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0">
                        <span class="badge bg-primary me-2">{{ $inProgressTasks->count() }}</span>
                        In Progress
                    </h6>
                    <i class="bi bi-arrow-repeat text-primary"></i>
                </div>
                <div class="card-body p-2" style="min-height: 300px; background: #f8fafc;">
                    @forelse($inProgressTasks as $task)
                    <div class="task-card mb-2" draggable="true" data-task-id="{{ $task->id }}">
                        <div class="card border-0 shadow-sm border-start border-3 border-primary">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 fw-bold">{{ $task->title }}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'review')"><i class="bi bi-eye me-2"></i>Move to Review</button></li>
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'completed')"><i class="bi bi-check-lg me-2"></i>Complete</button></li>
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'todo')"><i class="bi bi-arrow-left me-2"></i>Move Back</button></li>
                                        </ul>
                                    </div>
                                </div>
                                @if($task->description)
                                <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ 
                                        $task->priority == 'urgent' ? 'danger' : 
                                        ($task->priority == 'high' ? 'warning' : 
                                        ($task->priority == 'medium' ? 'info' : 'secondary')) 
                                    }}">{{ ucfirst($task->priority) }}</span>
                                    @if($task->assignedTo)
                                        <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 28px; height: 28px; font-size: 11px;" 
                                             title="{{ $task->assignedTo->name }}">
                                            {{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                @if($task->due_date)
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-calendar me-1"></i>{{ $task->due_date->format('M d') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-arrow-repeat" style="font-size: 32px;"></i>
                        <p class="mt-2 small">No tasks in progress</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Review Column --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning bg-opacity-10 border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0">
                        <span class="badge bg-warning me-2">{{ $reviewTasks->count() }}</span>
                        Review
                    </h6>
                    <i class="bi bi-eye text-warning"></i>
                </div>
                <div class="card-body p-2" style="min-height: 300px; background: #f8fafc;">
                    @forelse($reviewTasks as $task)
                    <div class="task-card mb-2" draggable="true" data-task-id="{{ $task->id }}">
                        <div class="card border-0 shadow-sm border-start border-3 border-warning">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 fw-bold">{{ $task->title }}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'completed')"><i class="bi bi-check-lg me-2"></i>Approve & Complete</button></li>
                                            <li><button class="dropdown-item" onclick="updateTaskStatus({{ $task->id }}, 'in_progress')"><i class="bi bi-arrow-left me-2"></i>Back to Progress</button></li>
                                        </ul>
                                    </div>
                                </div>
                                @if($task->description)
                                <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ 
                                        $task->priority == 'urgent' ? 'danger' : 
                                        ($task->priority == 'high' ? 'warning' : 
                                        ($task->priority == 'medium' ? 'info' : 'secondary')) 
                                    }}">{{ ucfirst($task->priority) }}</span>
                                    @if($task->assignedTo)
                                        <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 28px; height: 28px; font-size: 11px;" 
                                             title="{{ $task->assignedTo->name }}">
                                            {{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                @if($task->due_date)
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-calendar me-1"></i>{{ $task->due_date->format('M d') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-eye" style="font-size: 32px;"></i>
                        <p class="mt-2 small">No tasks to review</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Completed Column --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success bg-opacity-10 border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0">
                        <span class="badge bg-success me-2">{{ $completedTasks->count() }}</span>
                        Completed
                    </h6>
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <div class="card-body p-2" style="min-height: 300px; background: #f8fafc;">
                    @forelse($completedTasks as $task)
                    <div class="task-card mb-2">
                        <div class="card border-0 shadow-sm border-start border-3 border-success opacity-75">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-1 fw-bold text-decoration-line-through">{{ $task->title }}</h6>
                                @if($task->description)
                                <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Done</span>
                                    @if($task->assignedTo)
                                        <small class="text-muted">{{ $task->assignedTo->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 32px;"></i>
                        <p class="mt-2 small">No completed tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Task Modal --}}
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add New Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label fw-bold">Task Title *</label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   required placeholder="What needs to be done?">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="priority" class="form-label fw-bold">Priority</label>
                            <select class="form-select form-select-lg" id="priority" name="priority" required>
                                <option value="low">🟢 Low</option>
                                <option value="medium" selected>🟡 Medium</option>
                                <option value="high">🟠 High</option>
                                <option value="urgent">🔴 Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Add details about this task..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="assigned_to" class="form-label fw-bold">Assign To</label>
                            <select class="form-select" id="assigned_to" name="assigned_to">
                                <option value="">👤 Select Employee</option>
                                @foreach(\App\Models\Employee::with('user')->where('employment_status', 'active')->get() as $emp)
                                    <option value="{{ $emp->user_id }}">
                                        {{ $emp->user->name }} ({{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label fw-bold">Due Date</label>
                            <input type="date" class="form-select" id="due_date" name="due_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-plus-lg me-1"></i> Create & Assign Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.task-card {
    cursor: grab;
    transition: all 0.2s ease;
}
.task-card:hover {
    transform: translateY(-2px);
}
.task-card:active {
    cursor: grabbing;
}
.task-card .card {
    border-radius: 10px;
    transition: all 0.2s ease;
}
.task-card .card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.kanban-board .card-body {
    border-radius: 0 0 10px 10px;
}
.progress-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: conic-gradient(#3b82f6 calc(var(--progress, 0) * 1%), #e5e7eb 0);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.progress-circle::before {
    content: '';
    position: absolute;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: white;
}
.progress-circle-value {
    position: relative;
    z-index: 1;
    font-size: 11px;
    font-weight: 700;
    color: #1e3a8a;
}
</style>

@push('scripts')
<script>
    // Initialize progress circles
    document.querySelectorAll('.progress-circle').forEach(circle => {
        const progress = circle.dataset.progress;
        circle.style.setProperty('--progress', progress);
    });

    // Update task status via AJAX
    function updateTaskStatus(taskId, status) {
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
                showToast('Task moved to ' + status.replace('_', ' '), 'success');
                setTimeout(() => location.reload(), 500);
            }
        })
        .catch(error => {
            showToast('Error updating task', 'error');
        });
    }
</script>
@endpush
@endsection