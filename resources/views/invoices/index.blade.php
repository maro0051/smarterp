{{-- resources/views/invoices/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Invoices</h2>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Create Invoice
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="icon"><i class="bi bi-receipt"></i></div>
                <p>Total Invoices</p>
                <h3>{{ $invoices->total() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="icon"><i class="bi bi-check-circle"></i></div>
                <p>Paid Amount</p>
                <h3>${{ number_format($invoices->sum('paid_amount'), 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="icon"><i class="bi bi-clock"></i></div>
                <p>Pending Amount</p>
                <h3>${{ number_format($invoices->sum(function($i) { return $i->total_amount - $i->paid_amount; }), 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card purple">
                <div class="icon"><i class="bi bi-cash"></i></div>
                <p>Total Value</p>
                <h3>${{ number_format($invoices->sum('total_amount'), 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-primary fw-bold">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                            <td>${{ number_format($invoice->paid_amount, 2) }}</td>
                            <td>
                                @php $balance = $invoice->total_amount - $invoice->paid_amount; @endphp
                                <span class="{{ $balance > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                    ${{ number_format($balance, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $invoice->status == 'paid' ? 'success' : 
                                    ($invoice->status == 'sent' ? 'info' : 
                                    ($invoice->status == 'overdue' ? 'danger' : 
                                    ($invoice->status == 'draft' ? 'secondary' : 'secondary'))) 
                                }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                    @if($invoice->status == 'draft')
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-receipt" style="font-size: 48px;"></i>
                                <p class="mt-2">No invoices found</p>
                                <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create First Invoice</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $invoices->links() }}
    </div>
</div>
@endsection