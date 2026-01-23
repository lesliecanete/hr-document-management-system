<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - HR Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Background with Tagbilaran City photo - SAME AS LOGIN */
        body {
            min-height: 100vh;
            background-image: url('{{ asset("images/DO-Tagbilaran-City.png") }}');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        
        /* Semi-transparent overlay for better readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }
        
        .login-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Header - EXACT SAME AS LOGIN */
        .login-header {
            background: linear-gradient(135deg, rgba(31, 78, 121, 0.9) 0%, rgba(44, 107, 168, 0.9) 100%);
            color: white;
            padding: 25px 0;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }
        
        /* Card - EXACT SAME AS LOGIN */
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            margin-bottom: 40px;
        }
        
        /* Footer - EXACT SAME AS LOGIN */
        .login-footer {
            background: rgba(31, 78, 121, 0.9);
            color: white;
            padding: 20px 0;
            margin-top: auto;
            text-align: center;
            backdrop-filter: blur(5px);
        }
        
        .footer-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .footer-logos img {
            height: 45px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        
        .study-title {
            font-style: italic;
            font-size: 0.9rem;
            margin: 10px 0;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .copyright {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .password-hint {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        /* Input styling to match login */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: 0;
        }
        
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* Responsive adjustments - SAME AS LOGIN */
        @media (max-width: 768px) {
            .login-header {
                padding: 30px 0;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .footer-logos {
                gap: 20px;
            }
            
            .footer-logos img {
                height: 35px;
            }
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
                        class="me-2" style="height: 60px; width: auto; border-radius: 5px;"> 
                    HR Document Management System
                </h1>
                <p class="lead mb-0">Division of Tagbilaran City</p>
            </div>
        </div>

        <!-- Forgot Password Form -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card login-card">
                        <div class="card-header bg-white border-0 pt-4">
                            <h3 class="text-center mb-0 text-dark">
                                <i class="fas fa-key me-2"></i>{{ __('Reset Password') }}
                            </h3>
                            <p class="text-center text-muted mt-3">
                                Enter your email to receive a password reset link
                            </p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input id="email" type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" 
                                               required autocomplete="email" autofocus
                                               placeholder="Enter your registered email">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="password-hint mt-2">
                                        <i class="fas fa-info-circle me-1"></i> We'll send a password reset link to this email
                                    </div>
                                </div>

                                <!-- Submit Button - STYLED LIKE LOGIN BUTTON -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg py-3" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i> {{ __('Send Reset Link') }}
                                    </button>
                                </div>

                                <!-- Back to Login Link - SIMILAR TO LOGIN PAGE -->
                                <div class="text-center mt-4">
                                    <a class="text-decoration-none text-primary" href="{{ route('login') }}">
                                        <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Login') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section - EXACT SAME AS LOGIN -->
        <footer class="login-footer">
            <div class="container">
                <!-- Study Title -->
                <div class="study-title mb-3">
                    "A web-based electronic database management system for HR Department in the Division of Tagbilaran City"
                </div>
                
                <!-- Logos -->
                <div class="footer-logos">
                    <img src="{{ asset('images/deped-logo.png') }}" alt="DepEd Logo">
                    <img src="{{ asset('images/bagongpilipinas-logo.png') }}" alt="Bagong Pilipinas Logo">
                </div>
                
                <!-- Copyright -->
                <div class="copyright">
                    &copy; 2025 HR Document Management System | Division of Tagbilaran City
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Email validation
            const form = document.getElementById('resetForm');
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submitBtn');
            
            // Add focus effect to input (like login page)
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('border-primary');
            });
            
            emailInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('border-primary');
            });
            
            // Auto-focus email field
            emailInput.focus();
            
            // Form submission with validation
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    
                    // Remove any existing success alert
                    const successAlert = document.querySelector('.alert.alert-success');
                    if (successAlert) {
                        successAlert.remove();
                    }
                    
                    // Add error styling
                    emailInput.classList.add('is-invalid');
                    
                    // Create or update error message
                    let errorDiv = emailInput.parentNode.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block';
                        emailInput.parentNode.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Please enter a valid email address.';
                    
                    // Focus on the input
                    emailInput.focus();
                    
                    // Shake animation (like login page)
                    emailInput.style.animation = 'none';
                    setTimeout(() => {
                        emailInput.style.animation = 'shake 0.5s';
                    }, 10);
                } else {
                    // Add loading state (like login page enhancement)
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
                }
            });
            
            // Clear validation on input
            emailInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorDiv = this.parentNode.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            });
            
            // Add shake animation CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }
            `;
            document.head.appendChild(style);
            
            // Auto-dismiss success alert after 5 seconds
            setTimeout(function() {
                const alert = document.querySelector('.alert.alert-success');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    </script>
</body>
</html>