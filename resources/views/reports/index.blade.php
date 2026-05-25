{{-- resources/views/reports/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Reports Dashboard</h2>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up" style="font-size: 64px; color: #3b82f6;"></i>
                    <h4 class="mt-3">Sales Report</h4>
                    <p class="text-muted">View sales analytics, invoice reports, and revenue tracking</p>
                    <a href="{{ route('reports.sales') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam" style="font-size: 64px; color: #f97316;"></i>
                    <h4 class="mt-3">Inventory Report</h4>
                    <p class="text-muted">Product inventory levels, stock movements, and valuations</p>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-warning text-white">
                        <i class="bi bi-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack" style="font-size: 64px; color: #10b981;"></i>
                    <h4 class="mt-3">Financial Report</h4>
                    <p class="text-muted">Revenue vs expenses, profit/loss statements, and financial health</p>
                    <a href="{{ route('reports.financial') }}" class="btn btn-success">
                        <i class="bi bi-arrow-right me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Export Options</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('reports.export', 'sales-pdf') }}" class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf me-2"></i>Export Sales Report (PDF)
                        </a>
                        <a href="{{ route('reports.export', 'inventory-pdf') }}" class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf me-2"></i>Export Inventory Report (PDF)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection