<?php
// app/Http/Controllers/ProjectController.php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['projectManager', 'tasks'])
            ->withCount('tasks')
            ->latest()
            ->paginate(9);
        
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:projects',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'project_manager' => 'required|exists:users,id',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['projectManager', 'tasks' => function($query) {
            $query->orderBy('order');
        }, 'tasks.assignedTo']);
        
        // Group tasks by status for Kanban board
        $todoTasks = $project->tasks->where('status', 'todo');
        $inProgressTasks = $project->tasks->where('status', 'in_progress');
        $reviewTasks = $project->tasks->where('status', 'review');
        $completedTasks = $project->tasks->where('status', 'completed');
        
        return view('projects.show', compact('project', 'todoTasks', 'inProgressTasks', 'reviewTasks', 'completedTasks'));
    }

    public function edit(Project $project)
    {
        $users = \App\Models\User::all();
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:projects,code,' . $project->id,
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'budget' => 'nullable|numeric|min:0',
            'project_manager' => 'required|exists:users,id',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}