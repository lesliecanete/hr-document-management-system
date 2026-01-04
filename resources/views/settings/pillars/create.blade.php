<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create HR Pillar - HR Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-file-contract"></i> HR Document Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="documentsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-file-alt"></i> Documents
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('documents.create') }}">
                                    <i class="fas fa-upload"></i> Upload Document
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('documents.index') }}">
                                    <i class="fas fa-search"></i> Search Document
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('applicants.index') }}">
                            <i class="fas fa-user-tie"></i> Applicants
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="fas fa-users-cog"></i> Users
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('document-types.index') }}">
                                    <i class="fas fa-file-signature"></i> Document Types
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item active" href="{{ route('pillars.index') }}">
                                    <i class="fas fa-layer-group"></i> HR Pillars
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-plus-circle"></i> Create New HR Pillar
                        </h4>
                        <a href="{{ route('pillars.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Pillars
                        </a>
                    </div>
                    <div class="card-body">
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

                        <form method="POST" action="{{ route('pillars.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label required-field">Pillar Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Enter pillar name (e.g., Recruitment & Selection)" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> The display name for this HR pillar
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Enter a brief description of this HR pillar (optional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-align-left"></i> Describe the purpose and scope of this HR pillar
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required-field">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_active" id="active" 
                                           value="1" {{ old('is_active', 1) ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="active">
                                        <span class="badge bg-success">Active</span> - Pillar will be available for use
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_active" id="inactive" 
                                           value="0" {{ old('is_active') === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactive">
                                        <span class="badge bg-secondary">Inactive</span> - Pillar will be hidden from selection
                                    </label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                                <div>
                                    <a href="{{ route('pillars.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                                <div>
                                    <button type="reset" class="btn btn-outline-danger me-2">
                                        <i class="fas fa-redo"></i> Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create HR Pillar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-question-circle"></i> About HR Pillars
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>What are HR Pillars?</h6>
                                <p class="small text-muted">
                                    HR Pillars represent the main functional areas or categories within your Human Resources department. 
                                    They help organize documents and processes by specific HR functions.
                                </p>
                                <ul class="small text-muted">
                                    <li><strong>Examples:</strong> Recruitment, Training, Compensation, Employee Relations</li>
                                    <li><strong>Purpose:</strong> Categorize documents and streamline HR processes</li>
                                    <li><strong>Usage:</strong> Assigned to documents and linked to document types</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Best Practices</h6>
                                <ul class="small text-muted">
                                    <li>Use clear, descriptive names that team members will recognize</li>
                                    <li>Set pillars to inactive if they're no longer used (instead of deleting)</li>
                                    <li>Consider your organization's specific HR structure</li>
                                    <li>Keep pillar names concise but descriptive</li>
                                </ul>
                                <div class="alert alert-info small mb-0">
                                    <i class="fas fa-lightbulb"></i> 
                                    <strong>Tip:</strong> Common HR pillars include: Recruitment & Selection, 
                                    Learning & Development, Performance Management, Compensation & Benefits, 
                                    Employee Relations, and HR Compliance.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            
            if (!name.trim()) {
                e.preventDefault();
                alert('Please enter a pillar name.');
                return;
            }
        });
    </script>
</body>
</html>