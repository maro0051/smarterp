@extends('layouts.app')

@section('title', 'New Performance Review')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">New Performance Review</h2>
        <a href="{{ route('hr.performance.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hr.performance.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee *</label>
                            <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->user->name }} - {{ $employee->employee_code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="review_date" class="form-label">Review Date *</label>
                            <input type="date" name="review_date" id="review_date" class="form-control @error('review_date') is-invalid @enderror" 
                                   value="{{ old('review_date', date('Y-m-d')) }}" required>
                            @error('review_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Overall Rating *</label>
                            <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="">Select Rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} - {{ ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][$i-1] }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="achievements" class="form-label">Key Achievements</label>
                            <textarea name="achievements" id="achievements" class="form-control" rows="3">{{ old('achievements') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="improvements" class="form-label">Areas for Improvement</label>
                            <textarea name="improvements" id="improvements" class="form-control" rows="3">{{ old('improvements') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="goals" class="form-label">Future Goals</label>
                            <textarea name="goals" id="goals" class="form-control" rows="3">{{ old('goals') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="comments" class="form-label">Additional Comments</label>
                            <textarea name="comments" id="comments" class="form-control" rows="3">{{ old('comments') }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Submit Review
                </button>
                <a href="{{ route('hr.performance.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection