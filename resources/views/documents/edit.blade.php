@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
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

                            <!-- Display current applicant as read-only -->
                            @if($document->applicant_id && $document->applicant)
                                <div class="mb-3">
                                    <label class="form-label">Submitting Party </label>
                                    <div class="card">
                                        <div class="card-body py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user fa-2x me-3 text-primary"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $document->applicant->full_name }}</h6>
                                                    <div class="small text-muted">
                                                        @if($document->applicant->email)
                                                            <i class="fas fa-envelope me-1"></i>{{ $document->applicant->email }}
                                                        @endif
                                                        @if($document->applicant->phone)
                                                            <br><i class="fas fa-phone me-1"></i>{{ $document->applicant->phone }}
                                                        @endif
                                                        @if($document->applicant->position)
                                                            <br><i class="fas fa-briefcase me-1"></i>{{ $document->applicant->position }}
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <i class="fas fa-info-circle"></i> Submitting Party cannot be changed.
                                    </div>
                                    <!-- Hidden input to preserve applicant_id -->
                                    <input type="hidden" name="applicant_id" value="{{ $document->applicant_id }}">
                                </div>
                            @else
                                <div class="mb-3">
                                    <label class="form-label">Submitting Party</label>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        This document is not associated with any Submitting Party.
                                    </div>
                                    <!-- Hidden input with empty value -->
                                    <input type="hidden" name="applicant_id" value="">
                                </div>
                            @endif

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
                                <a href="{{ route('documents.index', $document) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const documentTypeSelect = document.getElementById('document_type_id');
        
        function updateApplicantInfo() {
            const selectedOption = documentTypeSelect.options[documentTypeSelect.selectedIndex];
            const requiresApplicant = selectedOption.getAttribute('data-requires-applicant') === '1';
            
            // Show informational message if document type requires applicant but no applicant is associated
            @if(!$document->applicant_id)
                if (requiresApplicant) {
                    // You could show a toast or alert here if needed
                    console.log('Selected document type requires an applicant, but this document has none.');
                }
            @endif
        }

        documentTypeSelect.addEventListener('change', updateApplicantInfo);
        updateApplicantInfo(); // Initialize
    });
    </script>
@endsection