@extends('layouts.app') {{-- Use your main layout --}}

@section('title', 'Access Denied')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-4x text-danger mb-3"></i>
                        <h1 class="display-5 fw-bold text-danger">Access Denied</h1>
                        <p class="lead">You don't have permission to access this page.</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Unauthorized Access:</strong> 
                        Your current role ({{ Auth::user()->role_display ?? 'Guest' }}) 
                        doesn't have the required permissions.
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Go to Dashboard
                        </a>
                    </div>
                    
                    @if(auth()->check())
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted mb-1">Your Information:</p>
                        <div class="d-flex justify-content-center gap-4">
                            <span class="badge bg-info">{{ Auth::user()->name }}</span>
                            <span class="badge {{ Auth::user()->role_badge }}">
                                {{ Auth::user()->role_display }}
                            </span>
                            <span class="badge bg-{{ Auth::user()->is_active ? 'success' : 'danger' }}">
                                {{ Auth::user()->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection