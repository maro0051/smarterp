{{-- resources/views/warehouses/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Warehouse Management</h2>
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Warehouse
        </a>
    </div>

    <div class="row">
        @forelse($warehouses as $warehouse)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $warehouse->name }}</h5>
                            <span class="badge bg-primary">{{ $warehouse->code }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('warehouses.show', $warehouse->id) }}" class="dropdown-item">
                                        <i class="bi bi-eye me-2"></i>View
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="dropdown-item">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete(this.form)">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $warehouse->address ?? 'No address' }}</p>
                    <p class="mb-1"><i class="bi bi-person me-2"></i>{{ $warehouse->manager_name ?? 'No manager' }}</p>
                    <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $warehouse->phone ?? 'No phone' }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">Stock Movements</small>
                            <h4>{{ $warehouse->stock_movements_count }}</h4>
                        </div>
                        <div>
                            <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'danger' }}">
                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-building" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No warehouses found</h4>
            <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Add First Warehouse</a>
        </div>
        @endforelse
    </div>
    <div class="mt-3">{{ $warehouses->links() }}</div>
</div>
@endsection