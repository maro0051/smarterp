{{-- resources/views/invoices/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Invoice #{{ $invoice->invoice_number }}</h2>
        <div>
            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-outline-secondary me-2" target="_blank">
                <i class="bi bi-file-pdf me-2"></i>Download PDF
            </a>
            @if($invoice->status == 'draft')
            <form action="{{ route('invoices.send', $invoice->id) }}" method="POST" class="d-inline me-2">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-send me-2"></i>Send Invoice
                </button>
            </form>
            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit Invoice
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Invoice Items -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>${{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                    <td>${{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                    <td>-${{ number_format($invoice->discount_amount, 2) }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($invoice->total_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Paid:</strong></td>
                                    <td class="text-success"><strong>${{ number_format($invoice->paid_amount, 2) }}</strong></td>
                                </tr>
                                <tr class="table-danger">
                                    <td colspan="4" class="text-end"><strong>Balance:</strong></td>
                                    <td><strong>${{ number_format($balance, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payments</h5>
                    @if($balance > 0)
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="bi bi-plus-lg"></i> Add Payment
                    </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Received By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                    <td>{{ $payment->receivedBy->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No payments received yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Invoice Details -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Status</label>
                        <h6>
                            <span class="badge bg-{{ 
                                $invoice->status == 'paid' ? 'success' : 
                                ($invoice->status == 'sent' ? 'info' : 
                                ($invoice->status == 'overdue' ? 'danger' : 'secondary')) 
                            }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Invoice Date</label>
                        <h6>{{ $invoice->invoice_date->format('F d, Y') }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Due Date</label>
                        <h6>{{ $invoice->due_date->format('F d, Y') }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Created By</label>
                        <h6>{{ $invoice->createdBy->name }}</h6>
                    </div>
                    @if($invoice->notes)
                    <div class="mb-3">
                        <label class="text-muted">Notes</label>
                        <p class="mb-0">{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $invoice->customer->name }}</h6>
                    @if($invoice->customer->company_name)
                        <p class="mb-1">{{ $invoice->customer->company_name }}</p>
                    @endif
                    @if($invoice->customer->email)
                        <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $invoice->customer->email }}</p>
                    @endif
                    @if($invoice->customer->phone)
                        <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $invoice->customer->phone }}</p>
                    @endif
                    @if($invoice->customer->address)
                        <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $invoice->customer->full_address }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
@if($balance > 0)
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <input type="hidden" name="customer_id" value="{{ $invoice->customer_id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Balance Due</label>
                        <input type="text" class="form-control" value="${{ number_format($balance, 2) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Payment Amount *</label>
                        <input type="number" name="amount" id="amount" class="form-control" 
                               max="{{ $balance }}" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date *</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method *</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" name="reference_number" id="reference_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection