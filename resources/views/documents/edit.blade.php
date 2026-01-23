@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit"></i> Edit Document: {{ $document->title }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data" id="documentForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column: Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Document Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $document->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="2">{{ old('description', $document->description) }}</textarea>
                                        @error('description')
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
                                        <input type="hidden" name="pillar" value="{{ $document->documentType->pillar_id }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="document_type_id" class="form-label">Document Type *</label>
                                        <select class="form-select @error('document_type_id') is-invalid @enderror" 
                                                id="document_type_id" name="document_type_id" required>
                                            <option value="">Select Document Type</option>
                                            @foreach($documentTypes as $docType)
                                                <option value="{{ $docType->id }}" 
                                                        {{ old('document_type_id', $document->document_type_id) == $docType->id ? 'selected' : '' }}
                                                        data-description="{{ $docType->description }}"
                                                        data-retention="{{ $docType->retention_years }}">
                                                    {{ $docType->name }} ({{ $docType->retention_years }} years)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('document_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Document Type Description Display -->
                                    <div id="documentTypeDescription" class="alert alert-info mb-3 {{ $document->documentType->description ? '' : 'd-none' }}">
                                        <div class="d-flex">
                                            <i class="fas fa-info-circle mt-1 me-2"></i>
                                            <div>
                                                <p class="mb-0 small" id="docTypeDescText">
                                                    {{ $document->documentType->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="document_date" class="form-label">Document Date *</label>
                                        <div class="input-group">
                                            <input type="date" 
                                                   class="form-control @error('document_date') is-invalid @enderror" 
                                                   id="document_date" 
                                                   name="document_date" 
                                                   value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}" 
                                                   required
                                                   onchange="updateDateDisplay(this)">
                                          
                                        </div>
                                        @error('document_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Date in MM/DD/YYYY: <strong id="dateDisplayText">{{ $document->document_date->format('m/d/Y') }}</strong>
                                        </small>
                                    </div>
                                </div>

                                <!-- Right Column: File & Submitting Party -->
                                <div class="col-md-6">
                                    <!-- Show current file information -->
                                    <div class="mb-3">
                                        <label class="form-label">Current Document File</label>
                                        @if($document->file_path)
                                        <div class="card mb-2">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file me-3 text-primary"></i>
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
                                        @else
                                        <div class="alert alert-warning py-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            No file currently attached.
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="document_file" class="form-label">Replace with New File</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                                id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('document_file').value=''">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</div>
                                        @error('document_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Display current applicant as read-only -->
                                    <div class="mb-3">
                                        <label class="form-label">Submitting Party</label>
                                        @if($document->applicant_id && $document->applicant)
                                        <div class="border rounded p-3 bg-light">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <div class="flex-grow-1">
                                                    <strong class="d-block">{{ $document->applicant->full_name }}</strong>
                                                    <small class="text-muted">
                                                        {{ $document->applicant->email }}
                                                        @if($document->applicant->position)
                                                        • {{ $document->applicant->position }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            @if($document->applicant->phone)
                                            <div class="small text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $document->applicant->phone }}
                                            </div>
                                            @endif
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Submitting Party cannot be changed for existing documents
                                        </small>
                                        <input type="hidden" name="applicant_id" value="{{ $document->applicant_id }}">
                                        @else
                                        <div class="alert alert-info py-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            This document is not associated with any Submitting Party.
                                        </div>
                                        <input type="hidden" name="applicant_id" value="">
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  id="notes" name="notes" rows="2" placeholder="Any additional notes...">{{ old('notes', $document->notes) }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Current Status -->
                                    <div class="mb-3">
                                        <label class="form-label">Document Status</label>
                                        <div class="d-flex">
                                               <x-document-status-badge :document="$document" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i> Back to List
                                        </a>
                                        <div>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fas fa-save me-1"></i> Update Document
                                            </button>
                                            <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-info ms-2">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
     function updateDateDisplay(input) {
            if (input.value) {
                const date = new Date(input.value);
                const formatted = `${String(date.getMonth() + 1).padStart(2, '0')}/${String(date.getDate()).padStart(2, '0')}/${date.getFullYear()}`;
                document.getElementById('dateDisplayText').textContent = formatted;
            }
    }
    document.addEventListener('DOMContentLoaded', function() {
       
        
        // Initialize on page load
        const datePicker = document.getElementById('document_date');
        if (datePicker) {
            updateDateDisplay(datePicker);
        }
        const documentTypeSelect = document.getElementById('document_type_id');
        const descriptionDiv = document.getElementById('documentTypeDescription');
        
        // Update description when document type is selected
        function updateDocumentTypeDescription() {
            const selectedOption = documentTypeSelect.options[documentTypeSelect.selectedIndex];
            
            if (selectedOption.value && selectedOption.dataset.description) {
                document.getElementById('docTypeDescText').textContent = selectedOption.dataset.description;
                descriptionDiv.classList.remove('d-none');
            } else {
                descriptionDiv.classList.add('d-none');
            }
        }
    });
    </script>
@endsection