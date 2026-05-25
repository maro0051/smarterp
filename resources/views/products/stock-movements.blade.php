{{-- resources/views/products/stock-movements.blade.php --}}
@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Stock Movements History</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reference</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('products.show', $movement->product_id) }}">
                                    {{ $movement->product->name }}
                                </a>
                            </td>
                            <td>{{ $movement->warehouse->name }}</td>
                            <td>
                                <span class="badge bg-{{ $movement->type == 'in' ? 'success' : ($movement->type == 'out' ? 'danger' : 'info') }}">
                                    {{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->reference_type ? $movement->reference_type . ' #' . $movement->reference_id : 'N/A' }}</td>
                            <td>{{ $movement->createdBy->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No stock movements found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $movements->links() }}</div>
</div>
@endsection