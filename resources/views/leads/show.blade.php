{{-- resources/views/leads/show.blade.php --}}
@extends('layouts.app')

@section('title', $lead->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $lead->name }}</h2>
        <div>
            @if($lead->status != 'won' && $lead->status != 'lost')
                <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-up-circle me-2"></i>Convert to Customer
                    </button>
                </form>
            @endif
            <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-warning ms-2">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Lead Details</h5></div>
                <div class="card-body">
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ 
                            $lead->status == 'won' ? 'success' : 
                            ($lead->status == 'lost' ? 'danger' : 
                            ($lead->status == 'new' ? 'info' : 'warning')) 
                        }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </p>
                    <p><strong>Email:</strong> {{ $lead->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $lead->phone ?? 'N/A' }}</p>
                    <p><strong>Company:</strong> {{ $lead->company_name ?? 'N/A' }}</p>
                    <p><strong>Source:</strong> {{ $lead->source ?? 'N/A' }}</p>
                    <p><strong>Potential Value:</strong> ${{ number_format($lead->potential_value ?? 0, 2) }}</p>
                    <p><strong>Expected Close:</strong> {{ $lead->expected_close_date ? $lead->expected_close_date->format('M d, Y') : 'N/A' }}</p>
                    <p><strong>Assigned To:</strong> {{ $lead->assignedTo->name ?? 'Unassigned' }}</p>
                    <p><strong>Created By:</strong> {{ $lead->createdBy->name ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @if($lead->notes)
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Notes</h5></div>
                    <div class="card-body">
                        <p>{{ $lead->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection