@extends('layouts.app')

@section('title', 'Request Leave')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Request Leave</h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Request Form</h5>
                </div>
                <div class="card-body">
                    @if(!$employee && !auth()->user()->can('view all leave requests'))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            You don't have an employee record. Please contact HR.
                        </div>
                    @else
                    <form action="{{ route('leave-requests.store') }}" method="POST">
                        @csrf

                        @can('view all leave requests')
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee *</label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->user->name ?? 'Unknown' }} ({{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @else
                        <div class="mb-3">
                            <label class="form-label">Employee</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            <input type="hidden" name="employee_id" value="{{ $employee->id ?? '' }}">
                        </div>
                        @endcan

                        <div class="mb-3">
                            <label for="leave_type_id" class="form-label">Leave Type *</label>
                            <select class="form-select @error('leave_type_id') is-invalid @enderror" 
                                    id="leave_type_id" name="leave_type_id" required>
                                <option value="">Select Leave Type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->days_per_year }} days/year)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason *</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="4" 
                                      placeholder="Please explain the reason for your leave request..." required>{{ old('reason') }}</textarea>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Submit Request
                        </button>
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Policy</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Leave Type</th><th>Days Per Year</th></tr>
                        </thead>
                        <tbody>
                            @foreach($leaveTypes as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>{{ $type->days_per_year }} days</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection