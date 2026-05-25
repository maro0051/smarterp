@extends('layouts.app')

@section('title', 'All ID Cards')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">All Employee ID Cards</h2>
        <div>
            <a href="{{ route('id-cards.download-all') }}" class="btn btn-success me-2">
                <i class="bi bi-download me-2"></i>Download All PDF
            </a>
            <button onclick="window.print()" class="btn btn-primary me-2">
                <i class="bi bi-printer me-2"></i>Print All
            </button>
            <a href="{{ route('id-cards.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        @foreach($employees as $employee)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); border-radius: 16px; color: white;">
                <div class="card-body p-3 text-center">
                    {{-- Company Name --}}
                    <small class="opacity-75">{{ \App\Models\CompanySetting::first()->company_name ?? 'SmartERP' }}</small>
                    
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    
                    {{-- Photo --}}
                    <div class="mb-2">
                        @if($employee->user->avatar)
                            <img src="{{ asset('storage/' . $employee->user->avatar) }}" 
                                 class="rounded border border-2 border-white" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded bg-white text-primary d-flex align-items-center justify-content-center mx-auto border border-2 border-white" 
                                 style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                                {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    {{-- Info --}}
                    <h6 class="mb-1">{{ $employee->user->name }}</h6>
                    <small class="d-block opacity-75">{{ $employee->position->name }}</small>
                    <strong class="d-block my-1">{{ $employee->employee_code }}</strong>
                    <small class="d-block opacity-75">{{ $employee->department->name }}</small>
                    
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    
                    <small class="opacity-75">Joined: {{ $employee->joining_date->format('M Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
@media print {
    .sidebar, .navbar-top, .btn, .page-content > .d-flex {
        display: none !important;
    }
    .main-content {
        margin-left: 0 !important;
    }
    .page-content {
        padding: 0 !important;
    }
    .col-xl-3 {
        width: 50% !important;
        page-break-inside: avoid;
    }
}
</style>
@endsection