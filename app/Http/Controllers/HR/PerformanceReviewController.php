<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\PerformanceReview;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        $reviews = PerformanceReview::with(['employee.user', 'reviewer'])
            ->latest()
            ->paginate(10);
        
        return view('hr.performance.index', compact('reviews'));
    }

    public function create()
    {
        $employees = Employee::with('user')->where('employment_status', 'active')->get();
        return view('hr.performance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_date' => 'required|date',
            'rating' => 'required|integer|between:1,5',
            'achievements' => 'nullable|string',
            'improvements' => 'nullable|string',
            'goals' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $validated['reviewer_id'] = Auth::id();
        $validated['status'] = 'submitted';

        PerformanceReview::create($validated);

        return redirect()->route('hr.performance.index')
            ->with('success', 'Performance review created successfully.');
    }

    public function show(PerformanceReview $performanceReview)
    {
        $performanceReview->load(['employee.user', 'reviewer']);
        return view('hr.performance.show', compact('performanceReview'));
    }
}