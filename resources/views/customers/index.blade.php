{{-- resources/views/customers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Customer Management</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Customer
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Code</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Invoices</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('customers.show', $customer->id) }}" class="fw-bold">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td>{{ $customer->code }}</td>
                            <td>{{ $customer->company_name ?? 'N/A' }}</td>
                            <td>
                                @if($customer->email)
                                    <small><i class="bi bi-envelope me-1"></i>{{ $customer->email }}</small><br>
                                @endif
                                @if($customer->phone)
                                    <small><i class="bi bi-telephone me-1"></i>{{ $customer->phone }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $customer->invoices_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(this.form)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-person-lines-fill" style="font-size: 48px;"></i>
                                <p class="mt-2">No customers found</p>
                                <a href="{{ route('customers.create') }}" class="btn btn-primary">Add First Customer</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $customers->links() }}</div>
</div>
@endsection