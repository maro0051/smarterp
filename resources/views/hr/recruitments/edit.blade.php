@extends('layouts.app')

@section('title', 'Edit Job Posting')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Job: {{ $recruitment->position }}</h2>
        <a href="{{ route('hr.recruitments.show', $recruitment->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Job
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hr.recruitments.update', $recruitment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position" class="form-label">Job Position *</label>
                            <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" 
                                   value="{{ old('position', $recruitment->position) }}" required>
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department *</label>
                            <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $recruitment->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="number_of_positions" class="form-label">Number of Positions *</label>
                            <input type="number" name="number_of_positions" id="number_of_positions" class="form-control" 
                                   value="{{ old('number_of_positions', $recruitment->number_of_positions) }}" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="open" {{ old('status', $recruitment->status) == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $recruitment->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="closed" {{ old('status', $recruitment->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="on_hold" {{ old('status', $recruitment->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="deadline" class="form-label">Application Deadline *</label>
                            <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" 
                                   value="{{ old('deadline', $recruitment->deadline ? $recruitment->deadline->format('Y-m-d') : '') }}" required>
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description *</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" required>{{ old('description', $recruitment->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea name="requirements" id="requirements" class="form-control" rows="3">{{ old('requirements', $recruitment->requirements) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="salary_range_min" class="form-label">Salary Min ($)</label>
                                <input type="number" name="salary_range_min" id="salary_range_min" class="form-control" 
                                       value="{{ old('salary_range_min', $recruitment->salary_range_min) }}" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label for="salary_range_max" class="form-label">Salary Max ($)</label>
                                <input type="number" name="salary_range_max" id="salary_range_max" class="form-control" 
                                       value="{{ old('salary_range_max', $recruitment->salary_range_max) }}" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save me-2"></i>Update Job Posting
                </button>
                <a href="{{ route('hr.recruitments.show', $recruitment->id) }}" class="btn btn-outline-secondary mt-3 ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection