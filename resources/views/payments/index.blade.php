{{-- resources/views/payments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Payment History</h2>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Received By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('invoices.show', $payment->invoice_id) }}">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $payment->customer->name }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                            <td>{{ $payment->receivedBy->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No payments recorded</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $payments->links() }}</div>
</div>
@endsection