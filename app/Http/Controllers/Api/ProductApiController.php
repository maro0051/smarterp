<?php
// app/Http/Controllers/Api/ProductApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'supplier'])->paginate(15);
        return response()->json($products);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'supplier', 'stockMovements']);
        return response()->json($product);
    }
}