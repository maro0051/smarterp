@extends('layouts.app')

@section('title', 'Payroll Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Payroll Management</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
            <i class="bi bi-plus-lg me-2"></i>Generate New Payroll
        </button>
    </div>

    <div class="row">
        @forelse($payrolls as $payroll)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $payroll->payroll_number }}</h5>
                    <span class="badge bg-{{ $payroll->status == 'paid' ? 'success' : ($payroll->status == 'processed' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <small class="text-muted d-block">Period</small>
                            <strong>{{ $payroll->period_start->format('M d') }} - {{ $payroll->period_end->format('M d') }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Payment Date</small>
                            <strong>{{ $payroll->payment_date->format('M d, Y') }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Total</small>
                            <strong class="text-success">${{ number_format($payroll->total_amount, 2) }}</strong>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Basic</th>
                                    <th>Net</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payroll->payslips->take(5) as $payslip)
                                <tr>
                                    <td>{{ $payslip->employee->user->name ?? 'Unknown' }}</td>
                                    <td>${{ number_format($payslip->basic_salary ?? 0, 0) }}</td>
                                    <td class="text-success fw-bold">${{ number_format($payslip->net_salary ?? 0, 0) }}</td>
                                    <td>
                                        <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($payroll->payslips->count() > 5)
                        <small class="text-muted">+ {{ $payroll->payslips->count() - 5 }} more employees</small>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $payroll->payslips->count() }} employees</small>
                        @if($payroll->status == 'processed')
                            <form action="{{ route('payslips.mark-paid', $payroll->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-lg me-1"></i> Mark as Paid
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-cash-stack" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No payrolls generated yet</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                <i class="bi bi-plus-lg me-2"></i>Generate First Payroll
            </button>
        </div>
        @endforelse
    </div>
    
    <div class="mt-3">{{ $payrolls->links() }}</div>
</div>

{{-- Generate Payroll Modal --}}
<div class="modal fade" id="generatePayrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payslips.generate') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-gear me-2"></i>Generate New Payroll</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Period Start *</label>
                        <input type="date" name="period_start" class="form-control" value="{{ date('Y-m-01') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Period End *</label>
                        <input type="date" name="period_end" class="form-control" value="{{ date('Y-m-t') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date *</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-t') }}" required>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>{{ \App\Models\Employee::where('employment_status', 'active')->count() }}</strong> active employees will be included.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>Generate Payroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection