{{-- resources/views/employees/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Employee Management</h2>
        @can('create employees')
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add New Employee
        </a>
        @endcan
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="icon"><i class="bi bi-people-fill"></i></div>
                <p>Total Employees</p>
                <h3>{{ $employees->total() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="icon"><i class="bi bi-check-circle"></i></div>
                <p>Active</p>
                <h3>{{ \App\Models\Employee::where('employment_status', 'active')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="icon"><i class="bi bi-clock"></i></div>
                <p>On Leave</p>
                <h3>{{ \App\Models\Employee::where('employment_status', 'on_leave')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card purple">
                <div class="icon"><i class="bi bi-diagram-3"></i></div>
                <p>Departments</p>
                <h3>{{ \App\Models\Department::count() }}</h3>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('employees.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email or code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach(\App\Models\Department::where('is_active', true)->get() as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resigned</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Employees Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px; font-size: 16px; flex-shrink: 0;">
                                        {{ $employee->user ? strtoupper(substr($employee->user->name, 0, 1)) : '?' }}
                                    </div>
                                    <div>
                                        <strong>{{ $employee->user->name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $employee->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employee->employee_code }}</td>
                            <td>
                                @if($employee->department)
                                    <span class="badge bg-info">{{ $employee->department->name }}</span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>{{ $employee->position->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ ($employee->employment_status ?? '') == 'active' ? 'success' : (($employee->employment_status ?? '') == 'on_leave' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $employee->employment_status ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>${{ number_format($employee->salary ?? 0, 2) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('edit employees')
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    @can('delete employees')
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(this.form)" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-people" style="font-size: 64px;"></i>
                                <h4 class="mt-3">No employees found</h4>
                                <p class="text-muted">
                                    @if(request('search') || request('department') || request('status'))
                                        No employees match your filter criteria. <a href="{{ route('employees.index') }}">Clear filters</a>
                                    @else
                                        Click the button below to add your first employee
                                    @endif
                                </p>
                                @if(!request('search') && !request('department') && !request('status'))
                                @can('create employees')
                                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Add Employee
                                </a>
                                @endcan
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} employees</small>
        {{ $employees->links() }}
    </div>
</div>
@endsection