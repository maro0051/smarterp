@extends('layouts.app')

@section('title', 'Payslip Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Payslip #{{ $payslip->payroll->payroll_number ?? 'N/A' }}</h2>
        <div>
            <a href="{{ route('payslips.download', $payslip->id) }}" class="btn btn-success me-2"><i class="bi bi-download me-1"></i> PDF</a>
            <a href="{{ route('payslips.print', $payslip->id) }}" class="btn btn-info me-2" target="_blank"><i class="bi bi-printer me-1"></i> Print</a>
            @if(auth()->user()->can('view employees'))
                <a href="{{ route('payslips.index') }}" class="btn btn-outline-secondary">Back</a>
            @else
                <a href="{{ route('payslips.my') }}" class="btn btn-outline-secondary">Back</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Employee Info</h5></div>
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" 
                         style="width: 80px; height: 80px; font-size: 32px;">
                        {{ strtoupper(substr($payslip->employee->user->name ?? '?', 0, 1)) }}
                    </div>
                    <h5>{{ $payslip->employee->user->name ?? 'Unknown' }}</h5>
                    <p class="text-muted">{{ $payslip->employee->employee_code ?? 'N/A' }}</p>
                    <hr>
                    <p><strong>Department:</strong> {{ $payslip->employee->department->name ?? 'N/A' }}</p>
                    <p><strong>Position:</strong> {{ $payslip->employee->position->name ?? 'N/A' }}</p>
                    <p><strong>Period:</strong> {{ $payslip->payroll->period_start->format('M d') ?? 'N/A' }} - {{ $payslip->payroll->period_end->format('M d, Y') ?? 'N/A' }}</p>
                    <p><strong>Payment Date:</strong> {{ $payslip->payroll->payment_date->format('M d, Y') ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ ($payslip->payroll->status ?? '') == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($payslip->payroll->status ?? 'N/A') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Salary Breakdown</h5></div>
                <div class="card-body">
                    <table class="table">
                        <thead><tr><th>Description</th><th class="text-end">Amount</th></tr></thead>
                        <tbody>
                            <tr><td>Basic Salary</td><td class="text-end">${{ number_format($payslip->basic_salary ?? 0, 2) }}</td></tr>
                            <tr><td class="text-success">Allowances</td><td class="text-end text-success">+${{ number_format($payslip->allowances ?? 0, 2) }}</td></tr>
                            <tr><td class="text-danger">Deductions</td><td class="text-end text-danger">-${{ number_format($payslip->deductions ?? 0, 2) }}</td></tr>
                            <tr><td class="text-danger">Tax</td><td class="text-end text-danger">-${{ number_format($payslip->tax ?? 0, 2) }}</td></tr>
                            <tr class="table-success"><th>Net Salary</th><th class="text-end">${{ number_format($payslip->net_salary ?? 0, 2) }}</th></tr>
                        </tbody>
                    </table>
                    <div class="text-center p-3 mt-3 rounded" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <small class="text-white opacity-75">Net Pay</small>
                        <h2 class="text-white mb-0">${{ number_format($payslip->net_salary ?? 0, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection