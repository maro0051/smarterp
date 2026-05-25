@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Notifications</h2>
    
    <div class="card">
        <div class="card-body p-0">
            @forelse(auth()->user()->notifications()->latest()->paginate(20) as $notification)
            <div class="p-3 border-bottom {{ $notification->is_read ? '' : 'bg-light' }}">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi {{ $notification->icon ?? 'bi-bell' }} fs-4 text-{{ $notification->type }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>{{ $notification->title }}</strong>
                        <p class="mb-0 text-muted">{{ $notification->message }}</p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$notification->is_read)
                    <span class="badge bg-primary">New</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-bell-slash" style="font-size: 48px; color: #94a3b8;"></i>
                <p class="mt-2 text-muted">No notifications</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection