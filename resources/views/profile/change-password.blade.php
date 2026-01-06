@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Change Password</h5>
                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Password must be at least 8 characters long.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            After changing your password, you'll need to log in again on other devices.
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-shield-alt me-2"></i>Password Security Tips</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Use at least 8 characters</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Mix uppercase and lowercase letters</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Include numbers and symbols</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Avoid using personal information</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Don't reuse old passwords</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection