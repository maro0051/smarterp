@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-list-check me-2"></i>My Tasks</h2>
        <a href="{{ route('projects.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-kanban me-1"></i> View Projects
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card blue">
                <div class="icon"><i class="bi bi-list-check"></i></div>
                <p>Total Tasks</p>
                <h3>{{ $allTasks->total() }}</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                <div class="icon"><i class="bi bi-inbox"></i></div>
                <p>To Do</p>
                <h3>{{ $allTasks->where('status', 'todo')->count() }}</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card orange">
                <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
                <p>In Progress</p>
                <h3>{{ $allTasks->where('status', 'in_progress')->count() }}</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card green">
                <div class="icon"><i class="bi bi-check-circle"></i></div>
                <p>Completed</p>
                <h3>{{ $allTasks->where('status', 'completed')->count() }}</h3>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('tasks.my') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-list me-1"></i> All ({{ $allTasks->total() }})
                </a>
                <a href="{{ route('tasks.my', ['status' => 'todo']) }}" class="btn btn-sm {{ request('status') == 'todo' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    <i class="bi bi-inbox me-1"></i> To Do
                </a>
                <a href="{{ route('tasks.my', ['status' => 'in_progress']) }}" class="btn btn-sm {{ request('status') == 'in_progress' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-arrow-repeat me-1"></i> In Progress
                </a>
                <a href="{{ route('tasks.my', ['status' => 'review']) }}" class="btn btn-sm {{ request('status') == 'review' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="bi bi-eye me-1"></i> In Review
                </a>
                <a href="{{ route('tasks.my', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="bi bi-check-lg me-1"></i> Completed
                </a>
            </div>
        </div>
    </div>

    {{-- Tasks Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35%;">Task</th>
                            <th>Project</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th style="width: 200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allTasks as $task)
                        <tr>
                            <td>
                                <a href="{{ route('tasks.view', $task->id) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                    <br><small class="text-muted">{{ Str::limit($task->description, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($task->project)
                                    <a href="{{ route('projects.show', $task->project_id) }}" class="text-decoration-none">
                                        <small>{{ $task->project->name }}</small>
                                    </a>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $task->priority == 'urgent' ? 'danger' : 
                                    ($task->priority == 'high' ? 'warning' : 
                                    ($task->priority == 'medium' ? 'info' : 'secondary')) 
                                }}">{{ ucfirst($task->priority) }}</span>
                            </td>
                            <td>
                                @if($task->due_date)
                                    <span class="{{ $task->due_date->isPast() && $task->status != 'completed' ? 'text-danger fw-bold' : '' }}">
                                        <i class="bi bi-calendar me-1"></i>{{ $task->due_date->format('M d, Y') }}
                                        @if($task->due_date->isPast() && $task->status != 'completed')
                                            <br><small class="text-danger">Overdue!</small>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $task->status == 'completed' ? 'success' : 
                                    ($task->status == 'in_progress' ? 'primary' : 
                                    ($task->status == 'review' ? 'warning' : 'secondary')) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    {{-- View Button --}}
                                    <a href="{{ route('tasks.view', $task->id) }}" class="btn btn-sm btn-outline-secondary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    {{-- Start Button --}}
                                    @if($task->status == 'todo')
                                        <button class="btn btn-sm btn-outline-primary" onclick="updateMyTask({{ $task->id }}, 'in_progress')" title="Start Working">
                                            <i class="bi bi-play-fill"></i> Start
                                        </button>
                                    @endif
                                    
                                    {{-- Submit Button --}}
                                    @if($task->status == 'in_progress')
                                        <button class="btn btn-sm btn-outline-warning" onclick="updateMyTask({{ $task->id }}, 'review')" title="Submit for Review">
                                            <i class="bi bi-send"></i> Submit
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="updateMyTask({{ $task->id }}, 'completed')" title="Mark as Done">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    @endif
                                    
                                    {{-- Review Status --}}
                                    @if($task->status == 'review')
                                        <span class="text-muted small">
                                            <i class="bi bi-hourglass-split me-1"></i>Under Review
                                        </span>
                                    @endif
                                    
                                    {{-- Completed Status --}}
                                    @if($task->status == 'completed')
                                        <span class="text-success small">
                                            <i class="bi bi-check-circle me-1"></i>Done
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                @if(request('status'))
                                    <i class="bi bi-inbox" style="font-size: 64px;"></i>
                                    <h4 class="mt-3">No {{ ucfirst(str_replace('_', ' ', request('status'))) }} Tasks</h4>
                                    <p>You don't have any tasks with this status.</p>
                                    <a href="{{ route('tasks.my') }}" class="btn btn-primary">View All Tasks</a>
                                @else
                                    <i class="bi bi-list-check" style="font-size: 64px;"></i>
                                    <h4 class="mt-3">No Tasks Assigned</h4>
                                    <p>You don't have any tasks yet. When your manager assigns tasks to you, they'll appear here.</p>
                                    <a href="{{ route('projects.index') }}" class="btn btn-primary">
                                        <i class="bi bi-kanban me-1"></i> View Projects
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Showing {{ $allTasks->firstItem() ?? 0 }} to {{ $allTasks->lastItem() ?? 0 }} of {{ $allTasks->total() }} tasks
        </small>
        {{ $allTasks->links() }}
    </div>
</div>

@push('scripts')
<script>
function updateMyTask(taskId, status) {
    const messages = {
        'in_progress': 'Task started! 🚀',
        'review': 'Task submitted for review! 📤',
        'completed': 'Task completed! ✅'
    };
    
    Swal.fire({
        title: 'Update Task?',
        text: 'Are you sure you want to ' + status.replace('_', ' ') + ' this task?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1e3a8a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, ' + status.replace('_', ' ') + '!'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    showToast(data.message || 'Error updating task', 'error');
                }
            })
            .catch(error => {
                showToast('Error updating task', 'error');
            });
        }
    });
}
</script>
@endpush
@endsection