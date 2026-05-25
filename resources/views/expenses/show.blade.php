{{-- resources/views/expenses/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Expense #' . $expense->expense_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Expense #{{ $expense->expense_number }}</h2>
        <div>
            @if($expense->status == 'pending')
                <form action="{{ route('expenses.approve', $expense->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i>Approve
                    </button>
                </form>
                <form action="{{ route('expenses.reject', $expense->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-2"></i>Reject
                    </button>
                </form>
            @endif
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Expense Details</h5></div>
                <div class="card-body">
                    <p><strong>Expense Number:</strong> {{ $expense->expense_number }}</p>
                    <p><strong>Date:</strong> {{ $expense->expense_date->format('F d, Y') }}</p>
                    <p><strong>Category:</strong> {{ $expense->category->name ?? 'N/A' }}</p>
                    <p><strong>Amount:</strong> ${{ number_format($expense->amount, 2) }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $expense->payment_method)) ?? 'N/A' }}</p>
                    <p><strong>Vendor:</strong> {{ $expense->vendor_name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $expense->status == 'approved' ? 'success' : ($expense->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </p>
                    <p><strong>Created By:</strong> {{ $expense->createdBy->name ?? 'Unknown' }}</p>
                    @if($expense->approvedBy)
                        <p><strong>{{ $expense->status == 'approved' ? 'Approved' : 'Rejected' }} By:</strong> {{ $expense->approvedBy->name ?? 'Unknown' }}</p>
                    @endif
                    @if($expense->description)
                        <p><strong>Description:</strong> {{ $expense->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @if($expense->receipt_image)
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Receipt</h5></div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $expense->receipt_image) }}" alt="Receipt" class="img-fluid">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection