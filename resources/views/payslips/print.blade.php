<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payslip->employee->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 { color: #1e3a8a; margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .info-table .label { font-weight: bold; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #1e3a8a; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .text-end { text-align: right; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
        .net-salary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ \App\Models\CompanySetting::first()->company_name ?? 'SmartERP' }}</h2>
        <p>Payslip for {{ $payslip->payroll->period_start->format('F Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Employee Name:</td>
            <td>{{ $payslip->employee->user->name }}</td>
            <td class="label">Payroll #:</td>
            <td>{{ $payslip->payroll->payroll_number }}</td>
        </tr>
        <tr>
            <td class="label">Employee Code:</td>
            <td>{{ $payslip->employee->employee_code }}</td>
            <td class="label">Pay Period:</td>
            <td>{{ $payslip->payroll->period_start->format('M d') }} - {{ $payslip->payroll->period_end->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Department:</td>
            <td>{{ $payslip->employee->department->name }}</td>
            <td class="label">Payment Date:</td>
            <td>{{ $payslip->payroll->payment_date->format('M d, Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td class="text-end">${{ number_format($payslip->basic_salary, 2) }}</td>
            </tr>
            <tr>
                <td>Allowances</td>
                <td class="text-end">${{ number_format($payslip->allowances, 2) }}</td>
            </tr>
            <tr>
                <td>Deductions</td>
                <td class="text-end text-danger">-${{ number_format($payslip->deductions, 2) }}</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td class="text-end text-danger">-${{ number_format($payslip->tax, 2) }}</td>
            </tr>
            <tr style="font-weight: bold; background: #f8fafc;">
                <td>Net Salary</td>
                <td class="text-end text-success">${{ number_format($payslip->net_salary, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="net-salary">
        <strong>Net Pay: ${{ number_format($payslip->net_salary, 2) }}</strong>
    </div>

    <div class="footer">
        This is a computer-generated document. No signature required.<br>
        {{ \App\Models\CompanySetting::first()->company_name ?? 'SmartERP' }}
    </div>
</body>
</html>