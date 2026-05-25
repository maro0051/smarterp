{{-- resources/views/reports/exports/sales-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 20px; }
        h1 { color: #1e3a8a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1e3a8a; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; background: #f8fafc; }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p>Generated on: {{ date('F d, Y') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Paid</th>
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
                <td>{{ ucfirst($invoice->status) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3"><strong>Totals:</strong></td>
                <td><strong>${{ number_format($invoices->sum('total_amount'), 2) }}</strong></td>
                <td><strong>${{ number_format($invoices->sum('paid_amount'), 2) }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>