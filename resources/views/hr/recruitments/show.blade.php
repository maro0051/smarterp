@extends('layouts.app')

@section('title', 'Job Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $recruitment->position }}</h2>
        <div>
            <a href="{{ route('hr.recruitments.edit', $recruitment->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('hr.recruitments.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Job Description</h5></div>
                <div class="card-body">{{ nl2br(e($recruitment->description)) }}</div>
            </div>

            @if($recruitment->requirements)
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Requirements</h5></div>
                <div class="card-body">{{ nl2br(e($recruitment->requirements)) }}</div>
            </div>
            @endif

            <!-- Interviews Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Interviews</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                        <i class="bi bi-plus-lg"></i> Schedule Interview
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recruitment->interviews as $interview)
                                <tr>
                                    <td>{{ $interview->candidate_name }}</td>
                                    <td>{{ $interview->candidate_email }}</td>
                                    <td>{{ \Carbon\Carbon::parse($interview->interview_date)->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $interview->status == 'completed' ? 'success' : 
                                            ($interview->status == 'scheduled' ? 'primary' : 
                                            ($interview->status == 'cancelled' ? 'danger' : 'warning')) 
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $interview->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($interview->rating)
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $interview->rating ? '-fill' : '' }} text-warning small"></i>
                                            @endfor
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No interviews scheduled</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Job Details</h5></div>
                <div class="card-body">
                    <p><strong>Department:</strong> {{ $recruitment->department->name }}</p>
                    <p><strong>Positions:</strong> {{ $recruitment->number_of_positions }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $recruitment->status == 'open' ? 'success' : ($recruitment->status == 'closed' ? 'danger' : 'warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $recruitment->status)) }}
                        </span>
                    </p>
                    <p><strong>Deadline:</strong> {{ $recruitment->deadline->format('F d, Y') }}</p>
                    @if($recruitment->salary_range_min && $recruitment->salary_range_max)
                        <p><strong>Salary Range:</strong> ${{ number_format($recruitment->salary_range_min) }} - ${{ number_format($recruitment->salary_range_max) }}</p>
                    @endif
                    <p><strong>Posted By:</strong> {{ $recruitment->createdBy->name }}</p>
                    <p><strong>Posted On:</strong> {{ $recruitment->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hr.interviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="recruitment_id" value="{{ $recruitment->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="candidate_name" class="form-label">Candidate Name *</label>
                        <input type="text" name="candidate_name" id="candidate_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidate_email" class="form-label">Candidate Email *</label>
                        <input type="email" name="candidate_email" id="candidate_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidate_phone" class="form-label">Phone</label>
                        <input type="text" name="candidate_phone" id="candidate_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="interview_date" class="form-label">Interview Date & Time *</label>
                        <input type="datetime-local" name="interview_date" id="interview_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection