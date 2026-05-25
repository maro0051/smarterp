{{-- resources/views/departments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Departments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Departments</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
            <i class="bi bi-plus-lg me-2"></i>Add Department
        </button>
    </div>

    <div class="row">
        @forelse($departments as $department)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $department->name }}</h5>
                            <span class="badge bg-primary">{{ $department->code }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('departments.show', $department->id) }}" class="dropdown-item">
                                        <i class="bi bi-eye me-2"></i>View
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('departments.edit', $department->id) }}" class="dropdown-item">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete(this.form)">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <p class="text-muted small">{{ $department->description ?? 'No description' }}</p>
                    
                    <div class="row mt-3">
                        <div class="col-6">
                            <small class="text-muted">Positions</small>
                            <h6>{{ $department->positions_count }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Employees</small>
                            <h6>{{ $department->employees_count }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-diagram-3" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No departments found</h4>
            <p class="text-muted">Create your first department to get started</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                <i class="bi bi-plus-lg me-2"></i>Add Department
            </button>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $departments->links() }}
    </div>
</div>

<!-- Create Department Modal -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Department Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Department Code *</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection