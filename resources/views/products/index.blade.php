{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Product Management</h2>
        <div>
            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left-right me-2"></i>Stock Movements
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 product-card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-box" style="font-size: 64px; color: #94a3b8;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                    @if($product->barcode)
                        <br><small class="text-muted">Barcode: {{ $product->barcode }}</small>
                    @endif
                    
                    <div class="mt-2">
                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                        @if($product->supplier)
                            <span class="badge bg-info">{{ $product->supplier->name }}</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">Cost</small>
                            <h6 class="mb-0">${{ number_format($product->cost_price, 2) }}</h6>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Selling</small>
                            <h6 class="mb-0 text-success">${{ number_format($product->selling_price, 2) }}</h6>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">Profit Margin:</small>
                        <strong class="text-success">
                            {{ number_format((($product->selling_price - $product->cost_price) / $product->selling_price) * 100, 1) }}%
                        </strong>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="btn-group w-100">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(this.form)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-box" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No products found</h4>
            <p class="text-muted">Add your first product to inventory</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>

<style>
.product-card {
    transition: all 0.3s ease;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
</style>
@endsection