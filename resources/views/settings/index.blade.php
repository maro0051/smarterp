{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Company Settings</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Company Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input type="text" name="company_name" id="company_name" 
                                       class="form-control" value="{{ $settings->company_name }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label">Company Email</label>
                                <input type="email" name="company_email" id="company_email" 
                                       class="form-control" value="{{ $settings->company_email }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_phone" class="form-label">Company Phone</label>
                                <input type="text" name="company_phone" id="company_phone" 
                                       class="form-control" value="{{ $settings->company_phone }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_website" class="form-label">Company Website</label>
                                <input type="text" name="company_website" id="company_website" 
                                       class="form-control" value="{{ $settings->company_website }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_tax_number" class="form-label">Tax Number</label>
                                <input type="text" name="company_tax_number" id="company_tax_number" 
                                       class="form-control" value="{{ $settings->company_tax_number }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <select name="currency" id="currency" class="form-select">
                                    <option value="USD" {{ $settings->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ $settings->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ $settings->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                                    <option value="INR" {{ $settings->currency == 'INR' ? 'selected' : '' }}>INR</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency_symbol" class="form-label">Currency Symbol</label>
                                <input type="text" name="currency_symbol" id="currency_symbol" 
                                       class="form-control" value="{{ $settings->currency_symbol }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select name="timezone" id="timezone" class="form-select">
                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}" {{ $settings->timezone == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="company_address" class="form-label">Company Address</label>
                                <textarea name="company_address" id="company_address" class="form-control" rows="3">{{ $settings->company_address }}</textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="company_logo" class="form-label">Company Logo</label>
                                @if($settings->company_logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $settings->company_logo) }}" 
                                             alt="Company Logo" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" name="company_logo" id="company_logo" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Laravel Version</small>
                        <h6>{{ app()->version() }}</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">PHP Version</small>
                        <h6>{{ phpversion() }}</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Database</small>
                        <h6>SQLite</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Users</small>
                        <h6>{{ \App\Models\User::count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection