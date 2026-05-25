{{-- resources/views/reports/inventory.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Inventory Report</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card blue">
                <p>Total Products</p>
                <h3>{{ $totalProducts }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card green">
                <p>Total Value</p>
                <h3>${{ number_format($totalValue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card orange">
                <p>Categories</p>
                <h3>{{ $categories->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Products by Category</h5></div>
        <div class="card-body">
            <canvas id="inventoryChart" height="300"></canvas>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format($product->cost_price, 2) }}</td>
                            <td>${{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                {{ $product->selling_price > 0 ? number_format((($product->selling_price - $product->cost_price) / $product->selling_price) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($categories->keys()) !!},
            datasets: [{
                data: {!! json_encode($categories->map(function($cat) { return $cat->count(); })) !!},
                backgroundColor: ['#3b82f6', '#f97316', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
@endsection