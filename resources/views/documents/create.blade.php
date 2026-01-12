@extends('layouts.app')

@section('title', 'Upload Document')

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

                            <!-- ========== APPLICANT TAB SECTION ========== -->
                            <div class="mb-4">
                                <label class="form-label">Applicant *</label>
                                
                                <!-- Applicant Selection Tabs -->
                                <ul class="nav nav-tabs mb-3" id="applicantTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="select-applicant-tab" data-bs-toggle="tab" 
                                                data-bs-target="#select-applicant-pane" type="button" role="tab">
                                            <i class="fas fa-search me-1"></i> Select Existing Applicant
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="create-applicant-tab" data-bs-toggle="tab" 
                                                data-bs-target="#create-applicant-pane" type="button" role="tab">
                                            <i class="fas fa-user-plus me-1"></i> Create New Applicant
                                        </button>
                                    </li>
                                </ul>
                                
                                <!-- Tab Content -->
                                <div class="tab-content border border-top-0 p-3" id="applicantTabContent">
                                    <!-- Tab 1: Select Existing Applicant -->
                                    <div class="tab-pane fade show active" id="select-applicant-pane" role="tabpanel">
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control" 
                                                       id="applicantSearch" 
                                                       placeholder="Search by name, email, or phone...">
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        id="clearSearch">
                                                    Clear
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div id="searchResults" style="max-height: 300px; overflow-y: auto;">
                                            <!-- Search results will appear here -->
                                            <div class="list-group">
                                                @foreach($applicants as $applicant)
                                                <label class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-check-input me-3" type="radio" 
                                                               name="applicant_id" 
                                                               value="{{ $applicant->id }}"
                                                               {{ old('applicant_id') == $applicant->id ? 'checked' : '' }}
                                                               id="applicant_{{ $applicant->id }}"
                                                               data-applicant-name="{{ $applicant->full_name }}">
                                                        <div>
                                                            <strong>{{ $applicant->full_name }}</strong>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-envelope me-1"></i>{{ $applicant->email }}
                                                                @if($applicant->phone)
                                                                <br><i class="fas fa-phone me-1"></i>{{ $applicant->phone }}
                                                                @endif
                                                                @if($applicant->applied_position)
                                                                <br><i class="fas fa-briefcase me-1"></i>{{ $applicant->applied_position }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-{{ $applicant->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($applicant->status) }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <div id="noResults" class="alert alert-info mt-3 d-none">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No applicants found. Try a different search or create a new applicant.
                                        </div>
                                        
                                        <div id="selectedApplicantInfo" class="alert alert-success mt-3 d-none">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <span id="selectedApplicantText"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Tab 2: Create New Applicant -->
                                    <div class="tab-pane fade" id="create-applicant-pane" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            New applicant will be created and automatically selected for this document.
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="new_first_name" class="form-label">First Name *</label>
                                                <input type="text" class="form-control @error('new_first_name') is-invalid @enderror" 
                                                       id="new_first_name" 
                                                       name="new_first_name"
                                                       value="{{ old('new_first_name') }}">
                                                @error('new_first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="new_last_name" class="form-label">Last Name *</label>
                                                <input type="text" class="form-control @error('new_last_name') is-invalid @enderror" 
                                                       id="new_last_name" 
                                                       name="new_last_name"
                                                       value="{{ old('new_last_name') }}">
                                                @error('new_last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="new_email" class="form-label">Email *</label>
                                                <input type="email" class="form-control @error('new_email') is-invalid @enderror" 
                                                       id="new_email" 
                                                       name="new_email"
                                                       value="{{ old('new_email') }}">
                                                @error('new_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="new_phone" class="form-label">Phone</label>
                                                <input type="text" class="form-control @error('new_phone') is-invalid @enderror" 
                                                       id="new_phone" 
                                                       name="new_phone"
                                                       value="{{ old('new_phone') }}">
                                                @error('new_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        
                                        <input type="hidden" id="applicant_created" name="applicant_created" value="0">
                                    </div>
                                </div>
                                
                                @error('applicant_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- ========== END APPLICANT TAB SECTION ========== -->

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

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pillarSelect = document.getElementById('pillar');
            const documentTypeSelect = document.getElementById('document_type_id');
            const documentTypeHelp = document.getElementById('documentTypeHelp');
            const submitBtn = document.getElementById('submitBtn');

            console.log('📄 Document upload page loaded');

            // ========== DOCUMENT TYPE LOADING ==========
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
                    documentTypeHelp.textContent = '⚠️ This document type requires an applicant association';
                    documentTypeHelp.className = 'form-text text-warning';
                } else {
                    documentTypeHelp.textContent = 'Applicant association is optional for this document type';
                    documentTypeHelp.className = 'form-text text-muted';
                }
            });
            
            // ========== APPLICANT TAB FUNCTIONALITY ==========
            const applicantCreatedInput = document.getElementById('applicant_created');
            const applicantSearchInput = document.getElementById('applicantSearch');
            const clearSearchBtn = document.getElementById('clearSearch');
            const searchResults = document.getElementById('searchResults');
            const noResults = document.getElementById('noResults');
            const selectedApplicantInfo = document.getElementById('selectedApplicantInfo');
            const selectedApplicantText = document.getElementById('selectedApplicantText');
            
            // Tab switching logic
            document.getElementById('create-applicant-tab').addEventListener('click', function() {
                applicantCreatedInput.value = 1;
                // Uncheck all existing applicant selections
                document.querySelectorAll('input[name="applicant_id"]').forEach(radio => {
                    radio.checked = false;
                });
                selectedApplicantInfo.classList.add('d-none');
            });
            
            document.getElementById('select-applicant-tab').addEventListener('click', function() {
                applicantCreatedInput.value = 0;
            });
            
            // Search functionality
            applicantSearchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const applicantItems = searchResults.querySelectorAll('.list-group-item');
                
                if (searchTerm.length === 0) {
                    applicantItems.forEach(item => item.style.display = 'flex');
                    noResults.classList.add('d-none');
                    return;
                }
                
                let found = false;
                applicantItems.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        item.style.display = 'flex';
                        found = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                if (found) {
                    noResults.classList.add('d-none');
                } else {
                    noResults.classList.remove('d-none');
                }
            });
            
            // Clear search
            clearSearchBtn.addEventListener('click', function() {
                applicantSearchInput.value = '';
                searchResults.querySelectorAll('.list-group-item').forEach(item => {
                    item.style.display = 'flex';
                });
                noResults.classList.add('d-none');
            });
            
            // When selecting an existing applicant
            document.querySelectorAll('input[name="applicant_id"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        applicantCreatedInput.value = 0;
                        const applicantName = this.getAttribute('data-applicant-name');
                        selectedApplicantText.textContent = `Selected: ${applicantName}`;
                        selectedApplicantInfo.classList.remove('d-none');
                        
                        // Switch to select tab if we're on create tab
                        document.getElementById('create-applicant-tab').classList.remove('active');
                        document.getElementById('select-applicant-tab').classList.add('active');
                        document.getElementById('create-applicant-pane').classList.remove('show', 'active');
                        document.getElementById('select-applicant-pane').classList.add('show', 'active');
                    }
                });
            });
            
            // Auto-switch to create tab if form had errors with new applicant
            @if(empty(old('applicant_id')) && (!empty(old('new_first_name')) || !empty(old('new_last_name')) || !empty(old('new_email'))))
                document.getElementById('create-applicant-tab').click();
            @endif
            
            // Form validation
            document.getElementById('documentForm').addEventListener('submit', function(e) {
                const applicantCreated = applicantCreatedInput.value;
                const hasSelectedApplicant = document.querySelector('input[name="applicant_id"]:checked') !== null;
                
                // Check if we're creating new applicant
                if (applicantCreated === '1') {
                    const firstName = document.getElementById('new_first_name').value.trim();
                    const lastName = document.getElementById('new_last_name').value.trim();
                    const email = document.getElementById('new_email').value.trim();
                    
                    if (!firstName || !lastName || !email) {
                        e.preventDefault();
                        alert('Please fill in all required fields for new applicant: First Name, Last Name, and Email.');
                        document.getElementById('create-applicant-tab').click();
                        return false;
                    }
                    
                    // Validate email format
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        alert('Please enter a valid email address for the new applicant.');
                        document.getElementById('new_email').focus();
                        return false;
                    }
                } 
                // Check if we selected existing applicant
                else if (!hasSelectedApplicant) {
                    e.preventDefault();
                    alert('Please either select an existing applicant or create a new one.');
                    return false;
                }
                
                // Additional validation for document type
                if (!documentTypeSelect.value) {
                    e.preventDefault();
                    alert('Please select a document type.');
                    documentTypeSelect.focus();
                    return false;
                }
            });
            
            console.log('💡 Tip: Open browser console (F12) to see debug messages');
        });
    </script>
@endsection