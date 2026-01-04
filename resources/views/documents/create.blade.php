@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-upload"></i> Upload Document
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" id="documentForm">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Document Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="pillar" class="form-label">HR Pillar *</label>
                                <select class="form-select @error('pillar') is-invalid @enderror" id="pillar" name="pillar" required>
                                    <option value="">Select HR Pillar</option>
                                    @foreach($pillars as $pillar)
                                        <option value="{{ $pillar->id }}" {{ old('pillar') == $pillar->id ? 'selected' : '' }}>
                                            {{ $pillar->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pillar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_type_id" class="form-label">Document Type *</label>
                                <select class="form-select @error('document_type_id') is-invalid @enderror" 
                                        id="document_type_id" name="document_type_id" required disabled>
                                    <option value="">First select an HR Pillar</option>
                                </select>
                                <div class="form-text" id="documentTypeHelp">
                                    Select an HR Pillar first to see available document types
                                </div>
                                @error('document_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="applicant_id" class="form-label">Applicant (if applicable)</label>
                                <select class="form-select @error('applicant_id') is-invalid @enderror" id="applicant_id" name="applicant_id">
                                    <option value="">Select Applicant</option>
                                    @foreach($applicants as $applicant)
                                        <option value="{{ $applicant->id }}" {{ old('applicant_id') == $applicant->id ? 'selected' : '' }}>
                                            {{ $applicant->full_name }} - {{ $applicant->applied_position }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('applicant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_date" class="form-label">Document Date *</label>
                                <input type="date" class="form-control @error('document_date') is-invalid @enderror" 
                                       id="document_date" name="document_date" value="{{ old('document_date') }}" required>
                                @error('document_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_file" class="form-label">Document File *</label>
                                <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                       id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</div>
                                @error('document_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-upload"></i> Upload Document
                                </button>
                                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
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
            const pillarSelect = document.getElementById('pillar');
            const documentTypeSelect = document.getElementById('document_type_id');
            const documentTypeHelp = document.getElementById('documentTypeHelp');
            const submitBtn = document.getElementById('submitBtn');

            console.log('📄 Document upload page loaded');

            // Load document types when pillar is selected
            pillarSelect.addEventListener('change', function() {
                const pillarId = this.value;
                console.log('🔄 Pillar selected:', pillarId);
                
                // Show loading state
                documentTypeSelect.innerHTML = '<option value="">Loading document types...</option>';
                documentTypeSelect.disabled = true;
                documentTypeHelp.textContent = 'Loading document types...';
                documentTypeHelp.className = 'form-text text-info';

                if (pillarId) {
                    console.log('📡 Fetching document types for pillar ID:', pillarId);
                    
                    fetch(`/get-document-types/${pillarId}`)
                        .then(response => {
                            console.log('📨 Response status:', response.status);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('✅ Received data:', data);
                            
                            // Clear the dropdown
                            documentTypeSelect.innerHTML = '<option value="">Select Document Type</option>';
                            
                            if (data && data.length > 0) {
                                // Add document types to dropdown
                                data.forEach(type => {
                                    const option = document.createElement('option');
                                    option.value = type.id;
                                    option.textContent = `${type.name} (${type.retention_years} years retention)`;
                                    
                                    // Add data attributes for employee requirement
                                    if (type.requires_person) {
                                        option.setAttribute('data-requires-person', 'true');
                                    }
                                    
                                    documentTypeSelect.appendChild(option);
                                });
                                
                                documentTypeHelp.textContent = `Found ${data.length} document type(s)`;
                                documentTypeHelp.className = 'form-text text-success';
                                console.log(`✅ Loaded ${data.length} document type(s)`);
                            } else {
                                // No document types found
                                documentTypeSelect.innerHTML = '<option value="">No document types available for this pillar</option>';
                                documentTypeHelp.textContent = 'No document types found for the selected HR Pillar';
                                documentTypeHelp.className = 'form-text text-warning';
                                console.log('⚠️ No document types found for this pillar');
                            }
                            
                            documentTypeSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('❌ Error fetching document types:', error);
                            documentTypeSelect.innerHTML = '<option value="">Error loading document types</option>';
                            documentTypeHelp.textContent = 'Error: Could not load document types. Please try again.';
                            documentTypeHelp.className = 'form-text text-danger';
                            documentTypeSelect.disabled = false;
                        });
                } else {
                    // No pillar selected
                    documentTypeSelect.innerHTML = '<option value="">Select Document Type</option>';
                    documentTypeSelect.disabled = true;
                    documentTypeHelp.textContent = 'Select an HR Pillar first to see available document types';
                    documentTypeHelp.className = 'form-text';
                }
            });

            // If pillar is already selected (from form validation), load its document types
            if (pillarSelect.value) {
                console.log('🔄 Pillar already selected, triggering change event');
                pillarSelect.dispatchEvent(new Event('change'));
            }
            
            // Add employee requirement logic based on selected document type
            documentTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const requiresPerson = selectedOption.getAttribute('data-requires-person') === 'true';
                
                if (requiresPerson) {
                    documentTypeHelp.textContent = '⚠️ This document type requires an employee association';
                    documentTypeHelp.className = 'form-text text-warning';
                } else {
                    documentTypeHelp.textContent = 'Employee association is optional for this document type';
                    documentTypeHelp.className = 'form-text text-muted';
                }
            });
            
            console.log('💡 Tip: Open browser console (F12) to see debug messages');
        });
    </script>
@endsection
