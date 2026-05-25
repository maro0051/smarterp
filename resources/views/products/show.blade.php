{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $product->name }}</h2>
        <div>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3" style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 300px;">
                            <i class="bi bi-box" style="font-size: 96px; color: #94a3b8;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    @if($product->barcode)
                        <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
                    @endif
                    <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    <p><strong>Supplier:</strong> {{ $product->supplier->name ?? 'N/A' }}</p>
                    <p><strong>Unit:</strong> {{ $product->unit }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Pricing -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Pricing Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Cost Price</small>
                                <h4 class="text-primary">${{ number_format($product->cost_price, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Selling Price</small>
                                <h4 class="text-success">${{ number_format($product->selling_price, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted">Profit Margin</small>
                                <h4 class="text-orange">
                                    {{ number_format((($product->selling_price - $product->cost_price) / $product->selling_price) * 100, 1) }}%
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Movements -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Stock Movement History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Warehouse</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->stockMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $movement->type == 'in' ? 'success' : 
                                            ($movement->type == 'out' ? 'danger' : 'info') 
                                        }}">
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->warehouse->name }}</td>
                                    <td>{{ $movement->reference_type ? $movement->reference_type . ' #' . $movement->reference_id : 'N/A' }}</td>
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