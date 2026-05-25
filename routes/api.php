<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes with 'api.' prefix
Route::prefix('v1')->middleware('auth:sanctum')->name('api.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'apiStats']);
    
    // Employees API
    Route::apiResource('/employees', App\Http\Controllers\Api\EmployeeApiController::class);
    
    // Products API
    Route::apiResource('/products', App\Http\Controllers\Api\ProductApiController::class);
    
    // Customers API
    Route::apiResource('/customers', App\Http\Controllers\Api\CustomerApiController::class);
    
    // Invoices API
    Route::apiResource('/invoices', App\Http\Controllers\Api\InvoiceApiController::class);
    
    // Expenses API
    Route::apiResource('/expenses', App\Http\Controllers\Api\ExpenseApiController::class);
    
    // Projects API
    Route::apiResource('/projects', App\Http\Controllers\Api\ProjectApiController::class);
    
    // Leads API
    Route::apiResource('/leads', App\Http\Controllers\Api\LeadApiController::class);
});