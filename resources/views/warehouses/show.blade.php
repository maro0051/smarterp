{{-- resources/views/warehouses/show.blade.php --}}
@extends('layouts.app')

@section('title', $warehouse->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $warehouse->name }}</h2>
        <div>
            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('warehouses.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Warehouse Details</h5></div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $warehouse->code }}</p>
                    <p><strong>Address:</strong> {{ $warehouse->address ?? 'N/A' }}</p>
                    <p><strong>Manager:</strong> {{ $warehouse->manager_name ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $warehouse->phone ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'danger' }}">
                            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Stock Movements</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouse->stockMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $movement->product->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->createdBy->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No stock movements</td>
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