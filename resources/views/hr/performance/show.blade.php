@extends('layouts.app')

@section('title', 'Performance Review')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Performance Review</h2>
        <a href="{{ route('hr.performance.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    @if(!$performanceReview->employee)
        <div class="alert alert-danger">This review has no associated employee. It may be corrupted.</div>
    @else
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Review Details</h5></div>
                <div class="card-body">
                    <p><strong>Employee:</strong> {{ $performanceReview->employee->user->name ?? 'Unknown' }}</p>
                    <p><strong>Department:</strong> {{ $performanceReview->employee->department->name ?? 'N/A' }}</p>
                    <p><strong>Position:</strong> {{ $performanceReview->employee->position->name ?? 'N/A' }}</p>
                    <p><strong>Reviewer:</strong> {{ $performanceReview->reviewer->name ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $performanceReview->review_date ? $performanceReview->review_date->format('F d, Y') : 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ ($performanceReview->status ?? '') == 'submitted' ? 'success' : (($performanceReview->status ?? '') == 'acknowledged' ? 'info' : 'warning') }}">
                            {{ ucfirst($performanceReview->status ?? 'N/A') }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Rating</h5></div>
                <div class="card-body text-center">
                    <div style="font-size: 48px;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= ($performanceReview->rating ?? 0) ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                    <h4 class="mt-2">
                        {{ ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][($performanceReview->rating ?? 1) - 1] }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @if($performanceReview->achievements)
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Key Achievements</h5></div>
                <div class="card-body">{{ nl2br(e($performanceReview->achievements)) }}</div>
            </div>
            @endif

            @if($performanceReview->improvements)
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Areas for Improvement</h5></div>
                <div class="card-body">{{ nl2br(e($performanceReview->improvements)) }}</div>
            </div>
            @endif

            @if($performanceReview->goals)
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Future Goals</h5></div>
                <div class="card-body">{{ nl2br(e($performanceReview->goals)) }}</div>
            </div>
            @endif

            @if($performanceReview->comments)
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Additional Comments</h5></div>
                <div class="card-body">{{ nl2br(e($performanceReview->comments)) }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection