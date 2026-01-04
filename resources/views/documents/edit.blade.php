<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $document->title }} - HR Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documents.index') }}">
                            <i class="fas fa-file-alt"></i> Documents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('applicants.index') }}">
                            <i class="fas fa-users"></i> Applicants
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
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
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
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
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit"></i> Edit Document: {{ $document->title }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Document Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $document->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Display current pillar as read-only -->
                            <div class="mb-3">
                                <label class="form-label">HR Pillar</label>
                                <div class="form-control bg-light">
                                    <strong>{{ $document->documentType->pillar->name }}</strong>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle"></i> Pillar cannot be changed for existing documents
                                    </small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="document_type_id" class="form-label">Document Type *</label>
                                <select class="form-select @error('document_type_id') is-invalid @enderror" 
                                        id="document_type_id" name="document_type_id" required>
                                    <option value="">Select Document Type</option>
                                    @foreach($documentTypes as $docType)
                                        <option value="{{ $docType->id }}" 
                                                {{ old('document_type_id', $document->document_type_id) == $docType->id ? 'selected' : '' }}
                                                data-requires-applicant="{{ $docType->requires_applicant ? '1' : '0' }}">
                                            {{ $docType->name }} ({{ $docType->retention_years }} years retention)
                                        </option>
                                    @endforeach
                                </select>
                                @error('document_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="applicant_id" class="form-label">Applicant</label>
                                <select class="form-select @error('applicant_id') is-invalid @enderror" id="applicant_id" name="applicant_id">
                                    <option value="">Select Applicant</option>
                                    @foreach($applicants as $applicant)
                                        <option value="{{ $applicant->id }}" 
                                                {{ old('applicant_id', $document->applicant_id) == $applicant->id ? 'selected' : '' }}>
                                            {{ $applicant->full_name }} - {{ $applicant->applied_position }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('applicant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="applicant-requirement-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="document_date" class="form-label">Document Date *</label>
                                <input type="date" class="form-control @error('document_date') is-invalid @enderror" 
                                       id="document_date" name="document_date" 
                                       value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}" required>
                                @error('document_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_file" class="form-label">Document File</label>
                                
                                <!-- Show current file information -->
                                @if($document->file_path)
                                <div class="card mb-3">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file fa-2x me-3 text-primary"></i>
                                            <div class="flex-grow-1">
                                                <strong class="d-block">{{ $document->file_name }}</strong>
                                                <small class="text-muted">
                                                    {{ $document->file_type }} • 
                                                    {{ number_format($document->file_size / 1024, 2) }} KB •
                                                    Uploaded: {{ $document->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                            <a href="{{ route('documents.download', $document) }}" 
                                            class="btn btn-sm btn-outline-primary" 
                                            target="_blank"
                                            title="Download current file">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text text-info mb-2">
                                    <i class="fas fa-info-circle"></i> Upload a new file below to replace the current one.
                                </div>
                                @endif
                                
                                <!-- File upload input -->
                                <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                    id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                
                                <div class="form-text">
                                    Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)
                                    @if(!$document->file_path)
                                        <br><span class="text-warning">No file currently attached.</span>
                                    @endif
                                </div>
                                @error('document_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="2">{{ old('notes', $document->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Document
                                </button>
                                <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const documentTypeSelect = document.getElementById('document_type_id');
        const applicantSelect = document.getElementById('applicant_id');
        const applicantRequirementText = document.getElementById('applicant-requirement-text');

        function updateApplicantRequirement() {
            const selectedOption = documentTypeSelect.options[documentTypeSelect.selectedIndex];
            const requiresApplicant = selectedOption.getAttribute('data-requires-applicant') === '1';
            
            if (requiresApplicant) {
                applicantRequirementText.innerHTML = '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> This document type requires an applicant association.</span>';
                applicantSelect.required = true;
            } else {
                applicantRequirementText.innerHTML = 'Optional: Associate this document with an applicant';
                applicantSelect.required = false;
            }
        }

        documentTypeSelect.addEventListener('change', updateApplicantRequirement);
        
        // Initialize on page load
        updateApplicantRequirement();
    });
    </script>

</body>
</html>