@extends('layouts.app')

@section('title', 'Post New Job')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Post New Job</h2>
        <a href="{{ route('hr.recruitments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hr.recruitments.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position" class="form-label">Job Position *</label>
                            <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" 
                                   value="{{ old('position') }}" required>
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department *</label>
                            <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="number_of_positions" class="form-label">Number of Positions *</label>
                            <input type="number" name="number_of_positions" id="number_of_positions" class="form-control" 
                                   value="{{ old('number_of_positions', 1) }}" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="deadline" class="form-label">Application Deadline *</label>
                            <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" 
                                   value="{{ old('deadline') }}" required>
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description *</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea name="requirements" id="requirements" class="form-control" rows="3">{{ old('requirements') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="salary_range_min" class="form-label">Salary Min</label>
                                <input type="number" name="salary_range_min" id="salary_range_min" class="form-control" 
                                       value="{{ old('salary_range_min') }}" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label for="salary_range_max" class="form-label">Salary Max</label>
                                <input type="number" name="salary_range_max" id="salary_range_max" class="form-control" 
                                       value="{{ old('salary_range_max') }}" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save me-2"></i>Post Job
                </button>
            </form>
        </div>
    </div>
</div>
@endsection