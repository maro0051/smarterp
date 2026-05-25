<?php
// app/Http/Controllers/Api/EmployeeApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeApiController extends Controller
{
    public function index(): JsonResponse
    {
        $employees = Employee::with(['user', 'department', 'position'])->paginate(15);
        return response()->json($employees);
    }

    public function show(Employee $employee): JsonResponse
    {
        $employee->load(['user', 'department', 'position', 'attendances', 'leaveRequests']);
        return response()->json($employee);
    }
}