{{-- resources/views/leads/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Leads')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Lead Management</h2>
        <a href="{{ route('leads.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Lead
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card blue">
                <p>Total Leads</p>
                <h3>{{ $totalLeads }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <p>Won</p>
                <h3>{{ $wonLeads }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <p>Conversion Rate</p>
                <h3>{{ $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100) : 0 }}%</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card purple">
                <p>Pipeline Value</p>
                <h3>${{ number_format($totalValue) }}</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Value</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                        <tr>
                            <td>
                                <a href="{{ route('leads.show', $lead->id) }}" class="fw-bold">
                                    {{ $lead->name }}
                                </a>
                                <br><small class="text-muted">{{ $lead->email }}</small>
                            </td>
                            <td>{{ $lead->company_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $lead->status == 'won' ? 'success' : 
                                    ($lead->status == 'lost' ? 'danger' : 
                                    ($lead->status == 'new' ? 'info' : 'warning')) 
                                }}">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td>{{ $lead->source ?? 'N/A' }}</td>
                            <td>${{ number_format($lead->potential_value ?? 0, 2) }}</td>
                            <td>{{ $lead->assignedTo->name ?? 'Unassigned' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('leads.show', $lead->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($lead->status != 'won')
                                    <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-up-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-graph-up" style="font-size: 48px;"></i>
                                <p class="mt-2">No leads found</p>
                                <a href="{{ route('leads.create') }}" class="btn btn-primary">Add First Lead</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $leads->links() }}</div>
</div>
@endsection