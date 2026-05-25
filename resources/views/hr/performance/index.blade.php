@extends('layouts.app')

@section('title', 'Performance Reviews')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Performance Reviews</h2>
        <a href="{{ route('hr.performance.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>New Review
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Reviewer</th>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->employee->user->name ?? 'Unknown' }}</td>
                            <td>{{ $review->reviewer->name ?? 'N/A' }}</td>
                            <td>{{ $review->review_date ? $review->review_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($review->rating ?? 0) ? '-fill' : '' }} text-warning"></i>
                                @endfor
                            </td>
                            <td>
                                <span class="badge bg-{{ ($review->status ?? '') == 'submitted' ? 'success' : (($review->status ?? '') == 'acknowledged' ? 'info' : 'warning') }}">
                                    {{ ucfirst($review->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('hr.performance.show', $review->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-star" style="font-size: 48px;"></i>
                                <p class="mt-2">No performance reviews found</p>
                                <a href="{{ route('hr.performance.create') }}" class="btn btn-primary">Create First Review</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $reviews->links() }}</div>
</div>
@endsection