<?php
// app/Http/Controllers/Api/ExpenseApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;

class ExpenseApiController extends Controller
{
    public function index(): JsonResponse
    {
        $expenses = Expense::with(['category', 'createdBy'])->paginate(15);
        return response()->json($expenses);
    }

    public function show(Expense $expense): JsonResponse
    {
        $expense->load(['category', 'createdBy', 'approvedBy']);
        return response()->json($expense);
    }
}