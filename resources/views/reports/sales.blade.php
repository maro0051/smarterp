{{-- resources/views/reports/sales.blade.php --}}
@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Sales Report</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Reports
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reports.sales') }}" method="GET" class="row">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card green">
                <p>Total Sales</p>
                <h3>${{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card blue">
                <p>Total Paid</p>
                <h3>${{ number_format($totalPaid, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <p>Outstanding</p>
                <h3>${{ number_format($totalDue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card purple">
                <p>Total Invoices</p>
                <h3>{{ $invoiceCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Invoice List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                            <td>${{ number_format($invoice->paid_amount, 2) }}</td>
                            <td>${{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection