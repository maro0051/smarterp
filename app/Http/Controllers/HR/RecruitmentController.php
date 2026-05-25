<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitmentController extends Controller
{
    public function index()
    {
        $recruitments = Recruitment::with(['department', 'createdBy'])
            ->latest()
            ->paginate(10);
        
        return view('hr.recruitments.index', compact('recruitments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('hr.recruitments.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'required|string',
            'number_of_positions' => 'required|integer|min:1',
            'deadline' => 'required|date|after:today',
            'requirements' => 'nullable|string',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0',
        ]);

        $validated['created_by'] = Auth::id();

        Recruitment::create($validated);

        return redirect()->route('hr.recruitments.index')
            ->with('success', 'Job posting created successfully.');
    }

    public function show(Recruitment $recruitment)
    {
        $recruitment->load(['department', 'createdBy', 'interviews.interviewer']);
        return view('hr.recruitments.show', compact('recruitment'));
    }

    public function edit(Recruitment $recruitment)
    {
        $departments = Department::where('is_active', true)->get();
        return view('hr.recruitments.edit', compact('recruitment', 'departments'));
    }

    public function update(Request $request, Recruitment $recruitment)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'required|string',
            'number_of_positions' => 'required|integer|min:1',
            'status' => 'required|in:open,in_progress,closed,on_hold',
            'deadline' => 'required|date',
            'requirements' => 'nullable|string',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0',
        ]);

        $recruitment->update($validated);

        return redirect()->route('hr.recruitments.index')
            ->with('success', 'Job posting updated successfully.');
    }

    public function destroy(Recruitment $recruitment)
    {
        $recruitment->delete();
        return redirect()->route('hr.recruitments.index')
            ->with('success', 'Job posting deleted successfully.');
    }
}