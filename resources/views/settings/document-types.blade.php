@extends('layouts.app')

@section('title', 'Document Types')

@section('content')
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-cog"></i> Document Types Settings
            </h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                <i class="fas fa-plus"></i> Add Document Type
            </button>
        </div>

        <!-- Document Types Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Document Types</h5>
            </div>
            <div class="card-body">
                @if($documentTypes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>HR Pillar</th>
                                    <th>Retention Years</th>
                                    <th style="display:none">Requires Employee</th>
                                    <th>Documents</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentTypes as $documentType)
                                <tr>
                                    <td>
                                        <strong>{{ $documentType->name }}</strong>
                                        @if($documentType->description)
                                            <br><small class="text-muted">{{ $documentType->description }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $documentType->pillar->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $documentType->retention_years }} years</span>
                                    </td>
                                    <td style="display:none">
                                        @if($documentType->requires_employee)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $documentType->documents()->count() > 0 ? 'primary' : 'secondary' }}">
                                            {{ $documentType->documents()->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($documentType->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editDocumentTypeModal"
                                                data-id="{{ $documentType->id }}"
                                                data-name="{{ $documentType->name }}"
                                                data-pillar="{{ $documentType->pillar_id }}"
                                                data-retention="{{ $documentType->retention_years }}"
                                                data-description="{{ $documentType->description }}"
                                                data-requires-employee="{{ $documentType->requires_employee ? '1' : '0' }}"
                                                data-is-active="{{ $documentType->is_active ? '1' : '0' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                          <!-- Add delete form -->
                                        <form action="{{ route('document-types.destroy', $documentType) }}" method="POST" 
                                            class="d-inline" onsubmit="return confirmDelete({{ $documentType->documents()->count() }})">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    title="{{ $documentType->documents()->count() > 0 ? 'Cannot delete: ' . $documentType->documents()->count() . ' documents associated' : 'Delete' }}"
                                                    @if($documentType->documents()->count() > 0) disabled @endif>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $documentTypes->links() }}
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No document types configured yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Document Type Modal -->
    <div class="modal fade" id="addDocumentTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('document-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Document Type Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pillar_id" class="form-label">HR Pillar *</label>
                            @if(isset($pillars) && $pillars->count() > 0)
                                <select class="form-select" name="pillar_id" required>
                                    <option value="">Select HR Pillar</option>
                                    @foreach($pillars as $pillar)
                                        <option value="{{ $pillar->id }}">{{ $pillar->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                 <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No HR pillars available.
                                    <a href="{{ route('pillars.index') }}" class="alert-link">Configure HR pillars first</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="retention_years" class="form-label">Retention Years *</label>
                            <input type="number" class="form-control" id="retention_years" name="retention_years" min="0" required>
                            <div class="form-text">Number of years to keep documents before archiving</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check" style="display: none">
                            <input type="checkbox" class="form-check-input" id="requires_employee" name="requires_employee" value="1">
                            <label class="form-check-label" for="requires_employee">Requires Employee Association</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Document Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Document Type Modal -->
    <div class="modal fade" id="editDocumentTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDocumentTypeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Document Type Name *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_pillar_id" class="form-label">HR Pillar *</label>
                            <select class="form-select" id="edit_pillar_id" name="pillar_id" required>
                                <option value="">Select HR Pillar</option>
                                @foreach($pillars as $pillar)
                                    <option value="{{ $pillar->id }}">{{ $pillar->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_retention_years" class="form-label">Retention Years *</label>
                            <input type="number" class="form-control" id="edit_retention_years" name="retention_years" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check" style="display:none">
                            <input type="checkbox" class="form-check-input" id="edit_requires_employee" name="requires_employee" value="1">
                            <label class="form-check-label" for="edit_requires_employee">Requires Employee Association</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Document Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <script>
        // Edit modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editDocumentTypeModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var pillarId = button.getAttribute('data-pillar');
                var retentionYears = button.getAttribute('data-retention');
                var description = button.getAttribute('data-description');
                var requiresEmployee = button.getAttribute('data-requires-employee');
                var isActive = button.getAttribute('data-is-active');
                
                // Update form action
                var form = document.getElementById('editDocumentTypeForm');
                form.action = '/settings/document-types/' + id;
                
                // Populate form fields
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_pillar_id').value = pillarId;
                document.getElementById('edit_retention_years').value = retentionYears;
                document.getElementById('edit_description').value = description;
                
                // Set checkboxes
                document.getElementById('edit_requires_employee').checked = (requiresEmployee === '1');
                document.getElementById('edit_is_active').checked = (isActive === '1');
            });
        });
    </script>
    @push('scripts')
    <script>
    function confirmDelete(documentCount) {
        if (documentCount > 0) {
            alert('Cannot delete this document type. There are ' + documentCount + ' documents associated with it.');
            return false;
        }
        
        return confirm('Are you sure you want to delete this document type?');
    }
    </script>
    @endpush
@endsection