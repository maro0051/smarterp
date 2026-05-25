@extends('layouts.app')

@section('title', 'System Health')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">System Health Check</h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">System Info</h5></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><td>Laravel Version</td><td><strong>{{ app()->version() }}</strong></td></tr>
                        <tr><td>PHP Version</td><td><strong>{{ phpversion() }}</strong></td></tr>
                        <tr><td>Database</td><td><strong>MySQL</strong></td></tr>
                        <tr><td>Total Users</td><td><strong>{{ \App\Models\User::count() }}</strong></td></tr>
                        <tr><td>Total Employees</td><td><strong>{{ \App\Models\Employee::count() }}</strong></td></tr>
                        <tr><td>Total Customers</td><td><strong>{{ \App\Models\Customer::count() }}</strong></td></tr>
                        <tr><td>Total Products</td><td><strong>{{ \App\Models\Product::count() }}</strong></td></tr>
                        <tr><td>Total Invoices</td><td><strong>{{ \App\Models\Invoice::count() }}</strong></td></tr>
                        <tr><td>Storage Used</td><td><strong>{{ round(disk_total_space(storage_path()) / 1024 / 1024 / 1024, 2) }} GB</strong></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection