@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Expense Management</h2>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Expense
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card orange">
                <div class="icon"><i class="bi bi-cash-coin"></i></div>
                <p>Total Approved</p>
                <h3>${{ number_format($totalExpenses ?? 0, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card blue">
                <div class="icon"><i class="bi bi-clock-history"></i></div>
                <p>Pending</p>
                <h3>${{ number_format($pendingExpenses ?? 0, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card green">
                <div class="icon"><i class="bi bi-list-check"></i></div>
                <p>Total Transactions</p>
                <h3>{{ $expenses->total() }}</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Expense #</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td>
                                <a href="{{ route('expenses.show', $expense->id) }}" class="text-primary fw-bold">
                                    {{ $expense->expense_number }}
                                </a>
                            </td>
                            <td>{{ $expense->expense_date ? $expense->expense_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $expense->category->name ?? 'N/A' }}</span>
                            </td>
                            <td>${{ number_format($expense->amount ?? 0, 2) }}</td>
                            <td>{{ $expense->vendor_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    ($expense->status ?? '') == 'approved' ? 'success' : 
                                    (($expense->status ?? '') == 'pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($expense->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>{{ $expense->createdBy->name ?? 'Unknown' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(($expense->status ?? '') == 'pending')
                                        @can('approve expenses')
                                        <form action="{{ route('expenses.approve', $expense->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        @endcan
                                        @can('reject expenses')
                                        <form action="{{ route('expenses.reject', $expense->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-cash-coin" style="font-size: 48px;"></i>
                                <p class="mt-2">No expenses found</p>
                                <a href="{{ route('expenses.create') }}" class="btn btn-primary">Add First Expense</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $expenses->links() }}
    </div>
</div>
@endsection