@extends('layouts.app')

@section('title', 'My Payslips')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">My Payslips</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payroll #</th>
                            <th>Period</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Tax</th>
                            <th>Net Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $payslip)
                        <tr>
                            <td>{{ $payslip->payroll->payroll_number }}</td>
                            <td>
                                {{ $payslip->payroll->period_start->format('M d') }} - 
                                {{ $payslip->payroll->period_end->format('M d, Y') }}
                            </td>
                            <td>${{ number_format($payslip->basic_salary, 2) }}</td>
                            <td class="text-success">+${{ number_format($payslip->allowances + $payslip->overtime + $payslip->bonus, 2) }}</td>
                            <td class="text-danger">-${{ number_format($payslip->deductions + $payslip->tax, 2) }}</td>
                            <td class="text-danger">-${{ number_format($payslip->tax, 2) }}</td>
                            <td>
                                <strong class="text-success">${{ number_format($payslip->net_salary, 2) }}</strong>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('payslips.download', $payslip->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('payslips.print', $payslip->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-file-earmark-text" style="font-size: 48px;"></i>
                                <p class="mt-2">No payslips found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $payslips->links() }}</div>
</div>
@endsection