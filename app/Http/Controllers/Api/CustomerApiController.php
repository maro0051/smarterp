<?php
// app/Http/Controllers/Api/CustomerApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerApiController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::withCount('invoices')->paginate(15);
        return response()->json($customers);
    }

    public function show(Customer $customer): JsonResponse
    {
        $customer->load(['invoices', 'payments']);
        return response()->json($customer);
    }
}