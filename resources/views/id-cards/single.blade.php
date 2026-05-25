@extends('layouts.app')

@section('title', 'ID Card - ' . $employee->user->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Employee ID Card</h2>
        <div>
            <a href="{{ route('id-cards.download', $employee->id) }}" class="btn btn-success me-2">
                <i class="bi bi-download me-2"></i>Download PDF
            </a>
            <a href="{{ route('id-cards.print', $employee->id) }}" class="btn btn-info me-2" target="_blank">
                <i class="bi bi-printer me-2"></i>Print
            </a>
            <a href="{{ route('id-cards.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5">
            {{-- Front of ID Card --}}
            <div class="card border-0 shadow-lg" id="id-card-front">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); border-radius: 16px; color: white;">
                    {{-- Company Header --}}
                    <div class="text-center mb-3">
                        <h5 class="mb-0">{{ \App\Models\CompanySetting::first()->company_name ?? 'SmartERP' }}</h5>
                        <small>Employee ID Card</small>
                    </div>
                    
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    
                    {{-- Photo --}}
                    <div class="text-center mb-3">
                        @if($employee->user->avatar)
                            <img src="{{ asset('storage/' . $employee->user->avatar) }}" 
                                 class="rounded border border-2 border-white" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded bg-white text-primary d-flex align-items-center justify-content-center mx-auto border border-2 border-white" 
                                 style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">
                                {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    {{-- Employee Info --}}
                    <div class="text-center">
                        <h4 class="mb-1">{{ $employee->user->name }}</h4>
                        <p class="mb-3 opacity-75">{{ $employee->position->name }}</p>
                        
                        <div class="row">
                            <div class="col-6 text-start">
                                <small class="opacity-75">Employee ID</small>
                                <p class="mb-1 fw-bold">{{ $employee->employee_code }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <small class="opacity-75">Department</small>
                                <p class="mb-1 fw-bold">{{ $employee->department->name }}</p>
                            </div>
                        </div>
                        
                        <hr style="border-color: rgba(255,255,255,0.3);">
                        
                        <small>Joining Date: {{ $employee->joining_date->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
            
            {{-- Print Button --}}
            <div class="text-center mt-3 no-print">
                <button onclick="window.print()" class="btn btn-primary btn-lg">
                    <i class="bi bi-printer me-2"></i>Print ID Card
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, .navbar-top, .no-print, .btn, .page-content > .d-flex {
        display: none !important;
    }
    .main-content {
        margin-left: 0 !important;
    }
    .page-content {
        padding: 0 !important;
    }
    #id-card-front {
        box-shadow: none !important;
        page-break-inside: avoid;
    }
}
</style>
@endsection