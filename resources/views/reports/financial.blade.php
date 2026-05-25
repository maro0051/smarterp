{{-- resources/views/reports/financial.blade.php --}}
@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Financial Report</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reports.financial') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Select Year</label>
                        <select name="year" class="form-select" onchange="this.form.submit()">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card green">
                <p>Total Revenue</p>
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card orange">
                <p>Total Expenses</p>
                <h3>${{ number_format($totalExpenses, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card blue">
                <p>Net Profit</p>
                <h3>${{ number_format($totalProfit, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Monthly Breakdown - {{ $year }}</h5></div>
        <div class="card-body">
            <canvas id="financialChart" height="400"></canvas>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Revenue</th>
                            <th>Expenses</th>
                            <th>Profit</th>
                            <th>Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(range(1, 12) as $month)
                        <tr>
                            <td>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</td>
                            <td>${{ number_format($monthlyRevenue[$month], 2) }}</td>
                            <td>${{ number_format($monthlyExpenses[$month], 2) }}</td>
                            <td class="{{ $monthlyProfit[$month] >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($monthlyProfit[$month], 2) }}
                            </td>
                            <td>
                                {{ $monthlyRevenue[$month] > 0 ? number_format(($monthlyProfit[$month] / $monthlyRevenue[$month]) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('financialChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(array_values($monthlyRevenue)) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: '#3b82f6',
                borderWidth: 1
            }, {
                label: 'Expenses',
                data: {!! json_encode(array_values($monthlyExpenses)) !!},
                backgroundColor: 'rgba(249, 115, 22, 0.5)',
                borderColor: '#f97316',
                borderWidth: 1
            }, {
                label: 'Profit',
                data: {!! json_encode(array_values($monthlyProfit)) !!},
                type: 'line',
                borderColor: '#10b981',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection