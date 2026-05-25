@extends('layouts.app')

@section('title', 'ID Cards')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Employee ID Cards</h2>
        <div>
            <a href="{{ route('id-cards.generate-all') }}" class="btn btn-primary me-2">
                <i class="bi bi-grid me-2"></i>View All Cards
            </a>
            <a href="{{ route('id-cards.download-all') }}" class="btn btn-success">
                <i class="bi bi-download me-2"></i>Download All PDF
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($employees as $employee)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    {{-- Employee Photo --}}
                    <div class="mb-3">
                        @if($employee->user->avatar)
                            <img src="{{ asset('storage/' . $employee->user->avatar) }}" 
                                 class="rounded-circle border border-3 border-primary" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto border border-3 border-primary" 
                                 style="width: 100px; height: 100px; font-size: 36px;">
                                {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h5 class="card-title mb-1">{{ $employee->user->name }}</h5>
                    <span class="badge bg-primary mb-2">{{ $employee->employee_code }}</span>
                    <p class="text-muted small mb-1">{{ $employee->position->name }}</p>
                    <p class="text-muted small mb-3">{{ $employee->department->name }}</p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('id-cards.generate', $employee->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i> View Card
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('id-cards.download', $employee->id) }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-download me-1"></i> PDF
                            </a>
                            <a href="{{ route('id-cards.print', $employee->id) }}" class="btn btn-outline-info btn-sm" target="_blank">
                                <i class="bi bi-printer me-1"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-credit-card" style="font-size: 64px; color: #94a3b8;"></i>
            <h4 class="mt-3 text-muted">No active employees found</h4>
        </div>
        @endforelse
    </div>
    
    <div class="mt-3">{{ $employees->links() }}</div>
</div>
@endsection