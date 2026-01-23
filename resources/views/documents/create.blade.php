@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-upload"></i> Upload Document
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" id="documentForm">
                            @csrf

                            <div class="row">
                                <!-- Left Column: Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Document Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="2">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
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

                                       
                                    </div>
                                    <div class="row">
                                         <div class="col-md-12 mb-3">
                                            <label for="document_type_id" class="form-label">Document Type *</label>
                                            <select class="form-select @error('document_type_id') is-invalid @enderror" 
                                                    id="document_type_id" name="document_type_id" required disabled>
                                                <option value="">First select HR Pillar</option>
                                            </select>
                                            <small class="form-text text-muted" id="documentTypeHelp">
                                                Select HR Pillar first
                                            </small>
                                            @error('document_type_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                                <div id="documentTypeDescription" class="alert alert-info mb-2 mt-2 d-none">
                                                    <div class="d-flex">
                                                        <div>
                                                            <strong id="docTypeName" class="d-block"></strong>
                                                            <p class="mb-0 small" id="docTypeDescText"></p>
                                                            <p class="mb-0 small mt-1">
                                                                <i class="fas fa-clock me-1"></i>
                                                                <span id="docTypeRetention"></span> 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                  
                                    <div class="mb-3">
                                        <label for="document_date" class="form-label">Document Date *</label>
                                        <input type="date" class="form-control @error('document_date') is-invalid @enderror" 
                                               id="document_date" name="document_date" value="{{ old('document_date') }}" required 
                                               onchange="updateDateDisplay(this)">
                                        @error('document_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Date in MM/DD/YYYY: <strong id="dateDisplayText"></strong>
                                        </small>
                                    </div>
                                </div>

                                <!-- Right Column: File & Submitting Party -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="document_file" class="form-label">Document File *</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                                   id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('document_file').value=''">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</div>
                                        @error('document_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  id="notes" name="notes" rows="2" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- ========== APPLICANT SECTION - COMPACT VERSION ========== -->
                                    <div class="mb-3">
                                        <label class="form-label">Submitting Party *</label>
                                        
                                        <!-- Compact Applicant Selection -->
                                        <div class="border rounded p-3">
                                            <!-- Tab Headers -->
                                            <div class="d-flex mb-3 border-bottom pb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary me-2 active" 
                                                        id="selectExistingBtn" onclick="switchApplicantTab('existing')">
                                                    <i class="fas fa-search me-1"></i> Select Existing
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        id="createNewBtn" onclick="switchApplicantTab('new')">
                                                    <i class="fas fa-user-plus me-1"></i> Create New
                                                </button>
                                            </div>
                                            
                                            <!-- Existing Applicant Selection (Initially visible) -->
                                            <div id="existingApplicantSection">
                                                <div class="input-group input-group-sm mb-2">
                                                    <input type="text" class="form-control form-control-sm" 
                                                           id="applicantSearch" placeholder="Search name, email, phone...">
                                                    <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                            id="clearSearch">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                
                                                <div id="searchResults" style="max-height: 150px; overflow-y: auto;" class="mb-2">
                                                    <div class="list-group list-group-flush">
                                                        @foreach($applicants as $applicant)
                                                        <label class="list-group-item list-group-item-action py-2">
                                                            <div class="form-check d-flex align-items-center mb-0">
                                                                <input class="form-check-input me-2" type="radio" 
                                                                       name="applicant_id" 
                                                                       value="{{ $applicant->id }}"
                                                                       {{ old('applicant_id') == $applicant->id ? 'checked' : '' }}
                                                                       id="applicant_{{ $applicant->id }}"
                                                                       onchange="selectApplicant(this)">
                                                                <div class="small">
                                                                    <strong class="d-block">{{ $applicant->full_name }}</strong>
                                                                    <span class="text-muted">
                                                                        {{ $applicant->email }}
                                                                        @if($applicant->position)
                                                                        • {{ $applicant->position }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                
                                                <div id="selectedApplicantInfo" class="alert alert-success py-2 d-none mb-0">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            <span id="selectedApplicantText"></span>
                                                        </span>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                onclick="clearApplicantSelection()">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div id="noResults" class="alert alert-info py-2 d-none">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    No results found. Try different keywords.
                                                </div>
                                            </div>
                                            
                                            <!-- New Applicant Form (Initially hidden) -->
                                            <div id="newApplicantSection" style="display: none;">
                                                <div class="alert alert-info py-2 mb-2">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    New submitting party will be created
                                                </div>
                                                
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <input type="text" class="form-control form-control-sm" 
                                                               id="new_first_name" name="new_first_name"
                                                               value="{{ old('new_first_name') }}" 
                                                               placeholder="First Name *">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="text" class="form-control form-control-sm" 
                                                               id="new_last_name" name="new_last_name"
                                                               value="{{ old('new_last_name') }}" 
                                                               placeholder="Last Name *">
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <input type="email" class="form-control form-control-sm" 
                                                               id="new_email" name="new_email"
                                                               value="{{ old('new_email') }}" 
                                                               placeholder="Email *">
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <input type="text" class="form-control form-control-sm" 
                                                               id="new_position" name="new_position"
                                                               value="{{ old('new_position') }}" 
                                                               placeholder="Position (optional)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @error('applicant_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- ========== END APPLICANT SECTION ========== -->
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i> Back to List
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                            <i class="fas fa-upload me-1"></i> Upload Document
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .list-group-item {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 0.5rem 0.75rem;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .form-control-sm {
            font-size: 0.875rem;
        }
    </style>
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
            
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pillarSelect = document.getElementById('pillar');
            const documentTypeSelect = document.getElementById('document_type_id');
            const documentTypeHelp = document.getElementById('documentTypeHelp');
            const applicantCreatedInput = document.createElement('input');
            applicantCreatedInput.type = 'hidden';
            applicantCreatedInput.name = 'applicant_created';
            applicantCreatedInput.value = '0';
            document.getElementById('documentForm').appendChild(applicantCreatedInput);

            // ========== DOCUMENT TYPE LOADING ==========
                       // ========== DOCUMENT TYPE LOADING ==========
            pillarSelect.addEventListener('change', function() {
                const pillarId = this.value;
                const descriptionDiv = document.getElementById('documentTypeDescription');
                
                documentTypeSelect.innerHTML = '<option value="">Loading...</option>';
                documentTypeSelect.disabled = true;
                documentTypeHelp.textContent = 'Loading document types...';
                descriptionDiv.classList.add('d-none');

                if (pillarId) {
                    fetch(`/get-document-types/${pillarId}`)
                        .then(response => response.json())
                        .then(data => {
                            documentTypeSelect.innerHTML = '<option value="">Select Document Type</option>';
                            
                            if (data && data.length > 0) {
                                data.forEach(type => {
                                    const option = document.createElement('option');
                                    option.value = type.id;
                                    option.textContent = `${type.name} (${type.retention_years} years)`;
                                    
                                    // Store description and retention in dataset
                                    option.dataset.description = type.description || '';
                                    option.dataset.retention = type.retention_years || 0;
                                    
                                    documentTypeSelect.appendChild(option);
                                });
                                
                                documentTypeHelp.textContent = `${data.length} document type(s) available`;
                            } else {
                                documentTypeSelect.innerHTML = '<option value="">No document types</option>';
                                documentTypeHelp.textContent = 'No document types found';
                            }
                            
                            documentTypeSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            documentTypeSelect.innerHTML = '<option value="">Error loading</option>';
                            documentTypeHelp.textContent = 'Error loading document types';
                            documentTypeSelect.disabled = false;
                        });
                } else {
                    documentTypeSelect.innerHTML = '<option value="">Select Document Type</option>';
                    documentTypeSelect.disabled = true;
                    documentTypeHelp.textContent = 'Select HR Pillar first';
                    descriptionDiv.classList.add('d-none');
                }
            });

            // Add this event listener to show description when document type is selected
            documentTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const descriptionDiv = document.getElementById('documentTypeDescription');
                
                if (this.value && selectedOption.dataset.description) {
                    document.getElementById('docTypeRetention').textContent = selectedOption.dataset.description;
                    document.getElementById('documentTypeHelp').textContent = "";
                    descriptionDiv.classList.remove('d-none');
                } else {
                    descriptionDiv.classList.add('d-none');
                }
            });
            
            // ========== APPLICANT TAB FUNCTIONALITY ==========
            window.switchApplicantTab = function(tab) {
                const selectExistingBtn = document.getElementById('selectExistingBtn');
                const createNewBtn = document.getElementById('createNewBtn');
                const existingSection = document.getElementById('existingApplicantSection');
                const newSection = document.getElementById('newApplicantSection');
                
                if (tab === 'existing') {
                    selectExistingBtn.classList.remove('btn-outline-primary');
                    selectExistingBtn.classList.add('btn-primary');
                    createNewBtn.classList.remove('btn-primary');
                    createNewBtn.classList.add('btn-outline-secondary');
                    existingSection.style.display = 'block';
                    newSection.style.display = 'none';
                    applicantCreatedInput.value = '0';
                    
                    // Uncheck new applicant inputs
                    document.querySelectorAll('#newApplicantSection input').forEach(input => {
                        input.value = '';
                    });
                } else {
                    selectExistingBtn.classList.remove('btn-primary');
                    selectExistingBtn.classList.add('btn-outline-primary');
                    createNewBtn.classList.remove('btn-outline-secondary');
                    createNewBtn.classList.add('btn-primary');
                    existingSection.style.display = 'none';
                    newSection.style.display = 'block';
                    applicantCreatedInput.value = '1';
                    
                    // Uncheck existing applicant radios
                    document.querySelectorAll('input[name="applicant_id"]').forEach(radio => {
                        radio.checked = false;
                    });
                    document.getElementById('selectedApplicantInfo').classList.add('d-none');
                }
            };

            window.selectApplicant = function(radio) {
                const selectedInfo = document.getElementById('selectedApplicantInfo');
                const selectedText = document.getElementById('selectedApplicantText');
                const applicantName = radio.closest('label').querySelector('strong').textContent;
                
                selectedText.textContent = `Selected: ${applicantName}`;
                selectedInfo.classList.remove('d-none');
                applicantCreatedInput.value = '0';
                
                // Switch to existing tab if needed
                window.switchApplicantTab('existing');
            };

            window.clearApplicantSelection = function() {
                document.querySelectorAll('input[name="applicant_id"]').forEach(radio => {
                    radio.checked = false;
                });
                document.getElementById('selectedApplicantInfo').classList.add('d-none');
            };

            // Search functionality
            document.getElementById('applicantSearch').addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const applicantItems = document.querySelectorAll('#searchResults .list-group-item');
                const noResults = document.getElementById('noResults');
                
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

            document.getElementById('clearSearch').addEventListener('click', function() {
                document.getElementById('applicantSearch').value = '';
                document.querySelectorAll('#searchResults .list-group-item').forEach(item => {
                    item.style.display = 'flex';
                });
                document.getElementById('noResults').classList.add('d-none');
            });

            // Form validation
            document.getElementById('documentForm').addEventListener('submit', function(e) {
                const applicantCreated = applicantCreatedInput.value;
                
                if (applicantCreated === '1') {
                    const firstName = document.getElementById('new_first_name').value.trim();
                    const lastName = document.getElementById('new_last_name').value.trim();
                    const email = document.getElementById('new_email').value.trim();
                    
                    if (!firstName || !lastName || !email) {
                        e.preventDefault();
                        alert('Please fill in all required fields for new applicant: First Name, Last Name, and Email.');
                        window.switchApplicantTab('new');
                        return false;
                    }
                    
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        alert('Please enter a valid email address.');
                        document.getElementById('new_email').focus();
                        return false;
                    }
                } else {
                    const hasSelectedApplicant = document.querySelector('input[name="applicant_id"]:checked') !== null;
                    if (!hasSelectedApplicant) {
                        e.preventDefault();
                        alert('Please select an existing applicant or create a new one.');
                        return false;
                    }
                }
                
                // Validate other required fields
                if (!documentTypeSelect.value) {
                    e.preventDefault();
                    alert('Please select a document type.');
                    return false;
                }
                
                if (!document.getElementById('document_file').value) {
                    e.preventDefault();
                    alert('Please select a file to upload.');
                    return false;
                }
            });

            // Initialize form state
            @if(old('applicant_created', '0') === '1' || (!empty(old('new_first_name')) && empty(old('applicant_id'))))
                window.switchApplicantTab('new');
            @endif
            
            if (pillarSelect.value) {
                pillarSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection