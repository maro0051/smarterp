{{-- resources/views/invoices/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 20px;
        }
        .company-info h1 {
            color: #1e3a8a;
            margin: 0;
            font-size: 28px;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            color: #f97316;
            margin: 0;
        }
        .customer-section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        thead th {
            background: #1e3a8a;
            color: white;
            padding: 10px;
            text-align: left;
        }
        tbody td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals table td {
            padding: 5px 10px;
            border: none;
        }
        .totals .total-row {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-paid { background: #10b981; color: white; }
        .status-sent { background: #3b82f6; color: white; }
        .status-overdue { background: #ef4444; color: white; }
        .status-draft { background: #6b7280; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            @php
                $settings = \App\Models\CompanySetting::first();
            @endphp
            <h1>{{ $settings->company_name }}</h1>
            <p>{{ $settings->company_address }}</p>
            <p>Phone: {{ $settings->company_phone }}</p>
            <p>Email: {{ $settings->company_email }}</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</p>
            <span class="status-badge status-{{ $invoice->status }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>
    </div>

    <div class="customer-section">
        <h3>Bill To:</h3>
        <p><strong>{{ $invoice->customer->name }}</strong></p>
        @if($invoice->customer->company_name)
            <p>{{ $invoice->customer->company_name }}</p>
        @endif
        <p>{{ $invoice->customer->address }}</p>
        <p>{{ $invoice->customer->city }}, {{ $invoice->customer->state }} {{ $invoice->customer->postal_code }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
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
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td>${{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Discount:</td>
                <td>-${{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total:</td>
                <td>${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Paid:</td>
                <td>${{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Balance Due:</td>
                <td>${{ number_format($balance, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($invoice->notes)
    <div style="clear: both; margin-top: 30px;">
        <h3>Notes:</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>{{ $settings->company_name }} | {{ $settings->company_website }}</p>
    </div>
</body>
</html>