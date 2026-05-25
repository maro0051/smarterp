<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recruitment_id' => 'required|exists:recruitments,id',
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email',
            'candidate_phone' => 'nullable|string',
            'interview_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $validated['interviewer_id'] = Auth::id();
        $validated['status'] = 'scheduled';

        Interview::create($validated);

        return redirect()->back()
            ->with('success', 'Interview scheduled successfully.');
    }

    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|in:1,2,3,4,5',
            'notes' => 'nullable|string',
        ]);

        $interview->update($validated);

        return redirect()->back()
            ->with('success', 'Interview updated successfully.');
    }
}