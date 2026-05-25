{{-- resources/views/suppliers/show.blade.php --}}
@extends('layouts.app')

@section('title', $supplier->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $supplier->name }}</h2>
        <div>
            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Supplier Details</h5></div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $supplier->code }}</p>
                    <p><strong>Contact Person:</strong> {{ $supplier->contact_person ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $supplier->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $supplier->phone ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $supplier->address ?? 'N/A' }}</p>
                    <p><strong>Tax Number:</strong> {{ $supplier->tax_number ?? 'N/A' }}</p>
                    <p><strong>Website:</strong> {{ $supplier->website ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $supplier->is_active ? 'success' : 'danger' }}">
                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Products Supplied</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplier->products as $product)
                                <tr>
                                    <td><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></td>
                                    <td>{{ $product->sku }}</td>
                                    <td>${{ number_format($product->cost_price, 2) }}</td>
                                    <td>${{ number_format($product->selling_price, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No products from this supplier</td>
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