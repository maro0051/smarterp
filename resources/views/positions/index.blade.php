{{-- resources/views/positions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Positions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Positions</h2>
        <a href="{{ route('positions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Position
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Salary Range</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($positions as $position)
                        <tr>
                            <td>{{ $position->name }}</td>
                            <td>{{ $position->code }}</td>
                            <td>{{ $position->department->name }}</td>
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
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(this.form)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No positions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $positions->links() }}</div>
</div>
@endsection