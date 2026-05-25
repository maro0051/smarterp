<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display tasks assigned to the logged-in employee
     */
    public function myTasks()
    {
        $query = Task::where('assigned_to', auth()->id())->with('project');
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        $allTasks = $query->latest()->paginate(15);
        
        return view('tasks.my', compact('allTasks'));
    }

    /**
     * Display a single task
     */
    public function view(Task $task)
    {
        $isAssigned = $task->assigned_to == auth()->id();
        $isProjectManager = $task->project && $task->project->project_manager == auth()->id();
        $isAdmin = auth()->user()->can('view employees');

        if (!$isAssigned && !$isProjectManager && !$isAdmin) {
            abort(403, 'You do not have permission to view this task.');
        }

        $task->load(['project', 'assignedTo', 'createdBy', 'project.projectManager']);
        
        return view('tasks.view', compact('task'));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['order'] = Task::where('project_id', $validated['project_id'])->count();
        $validated['status'] = 'todo';

        $task = Task::create($validated);

        if ($task->project) {
            $this->updateProjectProgress($task->project);
        }

        // Notify assigned employee
        if ($task->assigned_to) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'title' => 'New Task Assigned 📋',
                'message' => 'You have been assigned: "' . $task->title . '" in project "' . ($task->project->name ?? 'Unknown') . '". Priority: ' . ucfirst($task->priority),
                'type' => 'info',
                'icon' => 'bi bi-list-check',
                'link' => route('projects.show', $task->project_id),
            ]);
        }

        return back()->with('success', 'Task created and assigned successfully.');
    }

    /**
     * Update task status via AJAX
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed',
        ]);

        $isAssigned = $task->assigned_to == auth()->id();
        $isProjectManager = $task->project && $task->project->project_manager == auth()->id();
        $isAdmin = auth()->user()->can('view employees');

        if (!$isAssigned && !$isProjectManager && !$isAdmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $oldStatus = $task->status;
        $task->update($validated);

        if ($task->project) {
            $this->updateProjectProgress($task->project);
        }

        $user = auth()->user();
        $taskTitle = $task->title;
        $projectName = $task->project->name ?? 'Unknown Project';

        // Employee started working
        if ($validated['status'] == 'in_progress' && $oldStatus == 'todo') {
            if ($task->project && $task->project->project_manager && $task->project->project_manager != auth()->id()) {
                Notification::create([
                    'user_id' => $task->project->project_manager,
                    'title' => 'Task Started 🚀',
                    'message' => "{$user->name} started working on \"{$taskTitle}\" in \"{$projectName}\".",
                    'type' => 'info', 'icon' => 'bi bi-play-circle',
                    'link' => route('projects.show', $task->project_id),
                ]);
            }
        }

        // Employee submitted for review
        if ($validated['status'] == 'review') {
            if ($task->project && $task->project->project_manager) {
                Notification::create([
                    'user_id' => $task->project->project_manager,
                    'title' => 'Task Ready for Review 📤',
                    'message' => "{$user->name} submitted \"{$taskTitle}\" in \"{$projectName}\" for review.",
                    'type' => 'warning', 'icon' => 'bi bi-eye',
                    'link' => route('projects.show', $task->project_id),
                ]);
            }
        }

        // Task completed
        if ($validated['status'] == 'completed') {
            if ($task->project && $task->project->project_manager && $task->project->project_manager != auth()->id()) {
                Notification::create([
                    'user_id' => $task->project->project_manager,
                    'title' => 'Task Completed ✅',
                    'message' => "{$user->name} completed \"{$taskTitle}\" in \"{$projectName}\".",
                    'type' => 'success', 'icon' => 'bi bi-check-circle',
                    'link' => route('projects.show', $task->project_id),
                ]);
            }

            $hrUsers = \App\Models\User::role(['Super Admin', 'HR Manager'])->get();
            foreach ($hrUsers as $hrUser) {
                if ($hrUser->id != auth()->id()) {
                    Notification::create([
                        'user_id' => $hrUser->id,
                        'title' => 'Employee Task Completed ✅',
                        'message' => "{$user->name} completed \"{$taskTitle}\" in \"{$projectName}\".",
                        'type' => 'success', 'icon' => 'bi bi-check-circle',
                        'link' => route('projects.show', $task->project_id),
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'status' => $task->status]);
    }

    /**
     * Update task details
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,completed',
            'due_date' => 'nullable|date',
        ]);

        $oldAssignee = $task->assigned_to;
        $task->update($validated);

        if ($task->project) {
            $this->updateProjectProgress($task->project);
        }

        if ($validated['assigned_to'] && $validated['assigned_to'] != $oldAssignee) {
            Notification::create([
                'user_id' => $validated['assigned_to'],
                'title' => 'Task Assigned to You 📋',
                'message' => 'You have been assigned task: "' . $task->title . '" in project "' . ($task->project->name ?? 'Unknown') . '"',
                'type' => 'info', 'icon' => 'bi bi-list-check',
                'link' => route('projects.show', $task->project_id),
            ]);
        }

        return back()->with('success', 'Task updated successfully.');
    }

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $project = $task->project;
        $task->delete();
        if ($project) {
            $this->updateProjectProgress($project);
        }
        return back()->with('success', 'Task deleted successfully.');
    }

    /**
     * Update project progress based on completed tasks
     */
    private function updateProjectProgress(Project $project)
    {
        $totalTasks = $project->tasks()->count();
        if ($totalTasks > 0) {
            $completedTasks = $project->tasks()->where('status', 'completed')->count();
            $progress = round(($completedTasks / $totalTasks) * 100);
            $project->update(['progress_percentage' => $progress]);
            if ($progress == 100) {
                $project->update(['status' => 'completed']);
            } elseif ($progress > 0 && $project->status == 'planning') {
                $project->update(['status' => 'in_progress']);
            }
        }
    }
}