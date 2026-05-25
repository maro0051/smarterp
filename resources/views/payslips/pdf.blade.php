<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip PDF</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; margin: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 15px; }
        .header h3 { color: #1e3a8a; margin: 0; }
        .info-table { width: 100%; margin-bottom: 15px; font-size: 12px; }
        .info-table td { padding: 3px; }
        .label { font-weight: bold; width: 120px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px; }
        th { background: #1e3a8a; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .text-end { text-align: right; }
        .net { background: #10b981; color: white; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>{{ $companySettings->company_name ?? 'SmartERP' }}</h3>
        Payslip - {{ $payslip->payroll->period_start->format('F Y') }}
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Employee:</td><td>{{ $payslip->employee->user->name }}</td>
            <td class="label">Code:</td><td>{{ $payslip->employee->employee_code }}</td>
        </tr>
        <tr>
            <td class="label">Department:</td><td>{{ $payslip->employee->department->name }}</td>
            <td class="label">Payroll #:</td><td>{{ $payslip->payroll->payroll_number }}</td>
        </tr>
    </table>

    <table>
        <thead><tr><th>Description</th><th class="text-end">Amount</th></tr></thead>
        <tbody>
            <tr><td>Basic Salary</td><td class="text-end">${{ number_format($payslip->basic_salary, 2) }}</td></tr>
            <tr><td>Allowances</td><td class="text-end">${{ number_format($payslip->allowances, 2) }}</td></tr>
            <tr><td>Deductions</td><td class="text-end">-${{ number_format($payslip->deductions, 2) }}</td></tr>
            <tr><td>Tax</td><td class="text-end">-${{ number_format($payslip->tax, 2) }}</td></tr>
        </tbody>
    </table>

    <div class="net">Net Pay: ${{ number_format($payslip->net_salary, 2) }}</div>

    <div class="footer">Computer-generated document | {{ $companySettings->company_name ?? 'SmartERP' }}</div>
</body>
</html>