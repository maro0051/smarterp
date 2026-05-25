@extends('layouts.app')

@section('title', 'Punch In/Out')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Punch In / Out</h2>

    <div class="row">
        <div class="col-md-5">
            {{-- Current Status Card --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Today's Status</h5>
                </div>
                <div class="card-body text-center">
                    <div class="display-1 mb-3" id="currentTime">
                        {{ date('h:i:s A') }}
                    </div>
                    <p class="text-muted">{{ date('l, F d, Y') }}</p>
                    
                    @if($hasCheckedIn)
                        @if($hasCheckedOut)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                You have completed your attendance for today.
                                <br><strong>Time In:</strong> {{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }}
                                <br><strong>Time Out:</strong> {{ \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') }}
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-clock me-2"></i>
                                You are currently checked in.
                                <br><strong>Time In:</strong> {{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }}
                                <br><strong>Don't forget to check out!</strong>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            You haven't checked in today. Please enter your employee code.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-7">
            {{-- Punch In Card --}}
            @if(!$hasCheckedIn)
            <div class="card mb-3 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-in-right me-2"></i>Check In</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendance.check-in') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Enter Your Employee Code</label>
                            <input type="text" name="employee_code" class="form-control form-control-lg" 
                                   placeholder="e.g. EMP001" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Check In Now
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Punch Out Card --}}
            @if($hasCheckedIn && !$hasCheckedOut)
            <div class="card mb-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-right me-2"></i>Check Out</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendance.check-out') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Enter Your Employee Code</label>
                            <input type="text" name="employee_code" class="form-control form-control-lg" 
                                   placeholder="e.g. EMP001" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Check Out Now
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Info Card --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-clock text-primary me-2"></i>Work hours: 9:00 AM - 5:00 PM</li>
                        <li class="mb-2"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Arriving after 9:00 AM will be marked as Late</li>
                        <li class="mb-2"><i class="bi bi-person text-success me-2"></i>Your employee code is on your ID card</li>
                        <li><i class="bi bi-question-circle text-info me-2"></i>Contact HR if you forgot your employee code</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update time every second
    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: true 
        });
        document.getElementById('currentTime').textContent = timeStr;
    }
    setInterval(updateTime, 1000);
</script>
@endpush
@endsection