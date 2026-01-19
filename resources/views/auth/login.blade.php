<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Background with Tagbilaran City photo */
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
        
        .login-header {
            background: linear-gradient(135deg, rgba(31, 78, 121, 0.9) 0%, rgba(44, 107, 168, 0.9) 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            margin-bottom: 40px;
        }
        
        .login-footer {
            background: rgba(31, 78, 121, 0.9);
            color: white;
            padding: 25px 0;
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
        
        /* Responsive adjustments */
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
        <!-- Header Section -->
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

        <!-- Login Form -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card login-card">
                        <div class="card-header bg-white border-0 pt-4">
                            <h3 class="text-center mb-0 text-dark">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login to Your Account') }}
                            </h3>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                               placeholder="Enter your email" value="">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" required autocomplete="current-password"
                                               placeholder="Enter your password" value="">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg py-3">
                                        <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}
                                    </button>
                                </div>

                                @if (Route::has('password.request'))
                                    <div class="text-center mt-3">
                                        <a class="text-decoration-none text-primary" href="{{ route('password.request') }}">
                                            <i class="fas fa-key me-1"></i>{{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
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
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
            
            
            // Add focus effect to inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('border-primary');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('border-primary');
                });
            });
        });
    </script>
</body>
</html>