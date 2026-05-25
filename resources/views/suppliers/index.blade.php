{{-- resources/views/suppliers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Supplier Management</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Supplier
        </a>
    </div>

    <div class="row">
        @forelse($suppliers as $supplier)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $supplier->name }}</h5>
                            <span class="badge bg-info">{{ $supplier->code }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('suppliers.show', $supplier->id) }}" class="dropdown-item">
                                        <i class="bi bi-eye me-2"></i>View
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="dropdown-item">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete(this.form)">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    @if($supplier->contact_person)
                        <p class="mb-1"><i class="bi bi-person me-2"></i>{{ $supplier->contact_person }}</p>
                    @endif
                    @if($supplier->email)
                        <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $supplier->email }}</p>
                    @endif
                    @if($supplier->phone)
                        <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $supplier->phone }}</p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">Products</small>
                            <h4>{{ $supplier->products_count }}</h4>
                        </div>
                        <div>
                            <span class="badge bg-{{ $supplier->is_active ? 'success' : 'danger' }}">
                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-truck" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No suppliers found</h4>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add First Supplier</a>
        </div>
        @endforelse
    </div>
    <div class="mt-3">{{ $suppliers->links() }}</div>
</div>
@endsection