{{-- resources/views/customers/show.blade.php --}}
@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $customer->name }}</h2>
        <div>
            <a href="{{ route('invoices.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary me-2">
                <i class="bi bi-receipt me-2"></i>Create Invoice
            </a>
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Customer Details</h5></div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $customer->code }}</p>
                    <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                    <p><strong>Company:</strong> {{ $customer->company_name ?? 'N/A' }}</p>
                    <p><strong>Contact Person:</strong> {{ $customer->contact_person ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Financial Summary</h5></div>
                <div class="card-body">
                    <p><strong>Total Invoiced:</strong> ${{ number_format($customer->invoices->sum('total_amount'), 2) }}</p>
                    <p><strong>Total Paid:</strong> ${{ number_format($customer->invoices->sum('paid_amount'), 2) }}</p>
                    <p><strong>Outstanding:</strong> ${{ number_format($totalDue, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Recent Invoices</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->invoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice->id) }}">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No invoices found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection