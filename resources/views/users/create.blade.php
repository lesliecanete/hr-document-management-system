@extends('layouts.app')

@section('title', 'Add User')

@section('content')
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-plus"></i> Create New User
            </h1>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-circle"></i> User Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label required-field">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Enter full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label required-field">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="Enter email address" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label required-field">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Enter password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Minimum 8 characters
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label required-field">Confirm Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirm password" required>
                                    <div class="form-text">
                                        <i class="fas fa-shield-alt"></i> Re-enter the password for confirmation
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label required-field">Role</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select a role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                        <option value="hr_manager" {{ old('role') == 'hr_manager' ? 'selected' : '' }}>HR Manager</option>
                                        <option value="hr_staff" {{ old('role') == 'hr_staff' ? 'selected' : '' }}>HR Staff</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required-field">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active" id="active" 
                                               value="1" {{ old('is_active', 1) ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="active">
                                            <span class="badge bg-success">Active</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active" id="inactive" 
                                               value="0" {{ old('is_active') === '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactive">
                                            <span class="badge bg-secondary">Inactive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-3">
                                <div>
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                                <div>
                                    <button type="reset" class="btn btn-outline-danger me-2">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Role Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-shield-alt"></i> Role Permissions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-3">
                                <span class="badge bg-danger mb-2">Administrator</span>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-check text-success me-1"></i> Full system access</li>
                                    <li><i class="fas fa-check text-success me-1"></i> User management</li>
                                    <li><i class="fas fa-check text-success me-1"></i> System settings</li>
                                    <li><i class="fas fa-check text-success me-1"></i> All HR pillars</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-warning mb-2">HR Manager</span>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-check text-success me-1"></i> Manage documents</li>
                                    <li><i class="fas fa-check text-success me-1"></i> View all HR pillars</li>
                                    <li><i class="fas fa-check text-success me-1"></i> Applicant management</li>
                                    <li><i class="fas fa-times text-danger me-1"></i> Cannot manage users</li>
                                </ul>
                            </div>
                            <div class="mb-0">
                                <span class="badge bg-info mb-2">HR Staff</span>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-check text-success me-1"></i> Upload documents</li>
                                    <li><i class="fas fa-check text-success me-1"></i> View assigned pillars</li>
                                    <li><i class="fas fa-check text-success me-1"></i> Basic document management</li>
                                    <li><i class="fas fa-times text-danger me-1"></i> Limited access</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-lightbulb"></i> Quick Tips
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">
                            <div class="mb-3">
                                <strong>Password Requirements:</strong>
                                <ul class="mt-1">
                                    <li>Minimum 8 characters</li>
                                    <li>Use strong, unique passwords</li>
                                    <li>Consider using password generators</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>Role Selection:</strong>
                                <ul class="mt-1">
                                    <li>Assign minimum required permissions</li>
                                    <li>Review role descriptions carefully</li>
                                    <li>Start with lower privileges</li>
                                </ul>
                            </div>
                            <div class="mb-0">
                                <strong>User Status:</strong>
                                <ul class="mt-1">
                                    <li>Set to "Inactive" for temporary access</li>
                                    <li>Active users can login immediately</li>
                                    <li>Inactive users cannot access the system</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Password confirmation validation
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const confirmPassword = document.getElementById('password_confirmation');
            
            if (password && confirmPassword.value) {
                if (password !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        });

        document.getElementById('password_confirmation')?.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password && confirmPassword) {
                if (password !== confirmPassword) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check your entries.');
            }
        });
    </script>
@endsection
