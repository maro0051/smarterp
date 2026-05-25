<?php
// app/Http/Controllers/Api/LeadApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadApiController extends Controller
{
    public function index(): JsonResponse
    {
        $leads = Lead::with(['assignedTo', 'createdBy'])->paginate(15);
        return response()->json($leads);
    }

    public function show(Lead $lead): JsonResponse
    {
        $lead->load(['assignedTo', 'createdBy']);
        return response()->json($lead);
    }
}