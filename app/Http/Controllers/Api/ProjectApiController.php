<?php
// app/Http/Controllers/Api/ProjectApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectApiController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with(['projectManager', 'tasks'])->paginate(15);
        return response()->json($projects);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['projectManager', 'tasks.assignedTo']);
        return response()->json($project);
    }
}