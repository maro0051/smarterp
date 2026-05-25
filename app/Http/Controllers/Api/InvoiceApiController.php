<?php
// app/Http/Controllers/Api/InvoiceApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;

class InvoiceApiController extends Controller
{
    public function index(): JsonResponse
    {
        $invoices = Invoice::with(['customer', 'items'])->paginate(15);
        return response()->json($invoices);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load(['customer', 'items.product', 'payments', 'createdBy']);
        return response()->json($invoice);
    }
}