<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - HR Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-header {
            background: linear-gradient(135deg, #1F4E79 0%, #2c6ba8 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .password-hint {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header Section - EXACT SAME AS LOGIN -->
        <div class="login-header">
            <div class="container">
                <h1 class="display-4 mb-3">
                    <img src="{{ asset('images/depedtagbilaran-logo.jpg') }}" alt="DEPED Logo" 
                        class="me-2" style="height: 70px; width: auto;"> HR Document Management System
                </h1>
                <p class="lead">Division of Tagbilaran City</p>
            </div>
        </div>

        <!-- Forgot Password Form -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card login-card">
                        <div class="card-header bg-white border-0 pt-4">
                            <h3 class="text-center mb-0">{{ __('Reset Password') }}</h3>
                            <p class="text-center text-muted mt-2">
                                Enter your email to receive a password reset link
                            </p>
                        </div>

                        <div class="card-body p-5">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="email" type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" 
                                           required autocomplete="email" autofocus
                                           placeholder="Enter your registered email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="password-hint mt-2">
                                        <i class="fas fa-info-circle"></i> We'll send a password reset link to this email
                                    </div>
                                </div>

                                <!-- Submit Button - SAME AS LOGIN -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane"></i> {{ __('Send Reset Link') }}
                                    </button>
                                </div>

                                <!-- Back to Login Link - SIMILAR TO LOGIN PAGE -->
                                <div class="text-center mt-4">
                                    <a class="text-decoration-none" href="{{ route('login') }}">
                                        <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Login') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simple email validation enhancement
        document.querySelector('form').addEventListener('submit', function(e) {
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                e.preventDefault();
                // Add error styling
                emailInput.classList.add('is-invalid');
                
                // Create or update error message
                let errorDiv = emailInput.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    emailInput.parentNode.insertBefore(errorDiv, emailInput.nextSibling);
                }
                errorDiv.textContent = 'Please enter a valid email address.';
                
                // Focus on the input
                emailInput.focus();
            }
        });
        
        // Clear validation on input
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorDiv = this.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                errorDiv.remove();
            }
        });
        
        // Auto-focus email field
        document.getElementById('email').focus();
        
        // Auto-dismiss success alert after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert.alert-success');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>
</body>
</html>