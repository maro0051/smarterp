{{-- resources/views/departments/show.blade.php --}}
@extends('layouts.app')

@section('title', $department->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $department->name }}</h2>
        <div>
            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Department Details</h5>
                    <hr>
                    <p><strong>Code:</strong> {{ $department->code }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                            {{ $department->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p><strong>Description:</strong> {{ $department->description ?? 'No description' }}</p>
                    <p><strong>Created:</strong> {{ $department->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Positions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Positions ({{ $department->positions->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Code</th>
                                    <th>Salary Range</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($department->positions as $position)
                                <tr>
                                    <td>{{ $position->name }}</td>
                                    <td>{{ $position->code }}</td>
                                    <td>
                                        @if($position->salary_range_min && $position->salary_range_max)
                                            ${{ number_format($position->salary_range_min) }} - ${{ number_format($position->salary_range_max) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $position->is_active ? 'success' : 'danger' }}">
                                            {{ $position->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No positions in this department</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Employees -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Employees ({{ $department->employees->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($department->employees as $employee)
                                <tr>
                                    <td>
                                        <a href="{{ route('employees.show', $employee->id) }}">
                                            {{ $employee->user->name }}
                                        </a>
                                    </td>
                                    <td>{{ $employee->position->name }}</td>
                                    <td>{{ $employee->user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->employment_status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($employee->employment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No employees in this department</td>
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
@endsection