@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-file-alt text-primary"></i> Documents
                    </h1>
                    <p class="text-muted mb-0">Manage and search all documents</p>
                </div>
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    <i class="fas fa-upload me-1"></i> Upload Document
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter text-muted"></i> Search & Filters
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('documents.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Title, description, submitting party...">
                        </div>
                        <div class="col-md-3">
                            <label for="pillar" class="form-label">HR Pillar</label>
                            <select class="form-select" id="pillar" name="pillar">
                                <option value="">All Pillars</option>
                                @foreach($pillars as $pillar)
                                    <option value="{{ $pillar->name }}" {{ request('pillar') == $pillar->name ? 'selected' : '' }}>
                                        {{ $pillar->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status (Excluding Archived)</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expiring_soon" {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list text-muted"></i> Documents List
                            <span class="badge bg-secondary ms-2">{{ $documents->total() }} documents</span>
                        </h5>
                        <div class="d-flex gap-2">
                            @if(request()->anyFilled(['search', 'pillar', 'status']))
                                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i> Clear Filters
                                </a>
                            @endif
                            @php
                                $expiredCount = \App\Models\Document::where('status', 'expired')->count();
                                $buttonClass = $expiredCount > 0 ? 'btn-danger' : 'btn-secondary';
                            @endphp
                            <button type="button" 
                                class="btn {{ $buttonClass }} btn-sm"
                                data-bs-toggle="modal" 
                                data-bs-target="#archiveConfirmModal">
                            <i class="fas fa-archive me-1"></i> Archive Expired
                            <span class="badge bg-light text-dark ms-1">{{ $expiredCount }}</span>
                        </button>
                        </div>
                    </div>

                    <!-- Archive Confirmation Modal -->
                    <div class="modal fade" id="archiveConfirmModal" tabindex="-1" aria-labelledby="archiveConfirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header {{ $expiredCount > 0 ? 'bg-danger' : 'bg-secondary' }} text-white">
                                    <h5 class="modal-title" id="archiveConfirmModalLabel">
                                        <i class="fas fa-archive me-2"></i> Archive Expired Documents
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('documents.archive-expired') }}" method="POST" id="archiveForm">
                                    @csrf
                                    <div class="modal-body">
                                        @if($expiredCount > 0)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>{{ $expiredCount }}</strong> document(s) are currently expired.
                                            </div>
                                            <p>Are you sure you want to archive all expired documents?</p>
                                            <ul class="text-muted small mb-0">
                                                <li>Archived documents will no longer appear in active searches</li>
                                                <li>This action cannot be undone</li>
                                                <li>You can still view archived documents by filtering status to "Archived"</li>
                                            </ul>
                                        @else
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No expired documents found to archive.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </button>
                                        @if($expiredCount > 0)
                                            <button type="submit" class="btn btn-danger" id="submitArchive">
                                                <i class="fas fa-archive me-1"></i> Archive {{ $expiredCount }} Document(s)
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Optional: Add loading state JavaScript -->
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const archiveForm = document.getElementById('archiveForm');
                        const submitButton = document.getElementById('submitArchive');
                        
                        if (archiveForm && submitButton) {
                            archiveForm.addEventListener('submit', function(e) {
                                // Show loading state
                                const originalText = submitButton.innerHTML;
                                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Archiving...';
                                submitButton.disabled = true;
                                
                                // Allow form to submit
                                // Note: The page will reload, so this is just visual feedback
                            });
                        }
                    });
                    </script>
                </div>
                <div class="card-body p-0">
                    @if($documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Bar Code/QR</th>
                                        <th>Title</th>
                                        <th>Pillar</th>
                                        <th>Document Type/Retention Period</th>
                                        <th>Submitting Party</th>
                                        <th>Document Date</th>
                                        <th>Expiry Date</th>
                                        <th>Uploaded By</th> 
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                #{{ str_pad($document->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td style="vertical-align: top; text-align:center">
                                           <button type="button" 
                                                    class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#qrCodeModal{{ $document->id }}">
                                                <i class="fas fa-qrcode"></i>
                                            </button>
                                            <!-- Modal for each document -->
                                            <div class="modal fade" id="qrCodeModal{{ $document->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">QR Code: {{ Str::limit($document->title, 20) }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ route('documents.qrcode', $document) }}" 
                                                                    alt="QR Code for {{ $document->file_name }}"
                                                                    class="img-fluid rounded border shadow-sm"
                                                                    style="max-width: 150px;">
                                                            </div>
                                                            <p class="small text-muted mb-2 mt-2">
                                                                Scan to view document
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($document->file_path)
                                                    <i class="fas fa-file text-primary me-2"></i>
                                                @else
                                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                                @endif
                                                <div>
                                                    <a href="{{ route('documents.show', $document) }}" class="text-decoration-none">
                                                        <strong>{{ Str::limit($document->title, 40) }}</strong>
                                                    </a>
                                                    @if($document->description)
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($document->description, 30) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">{{ $document->documentType->pillar->name ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $document->documentType->name ?? 'N/A' }}</span>
                                            <br>
                                            <small class="text-muted">Retention: {{ $document->documentType->retention_years }} years</small>
                                        </td>
                                        <td>
                                            @if($document->applicant)
                                                <a href="{{ route('submitting-parties.show', $document->applicant) }}" class="text-primary">
                                                    {{ $document->applicant->full_name ?? $document->applicant->first_name . ' ' . ($document->applicant->last_name ?? '') }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $document->document_date->format('M d, Y') }}
                                        </td>
                                        <td>
                                            @if($document->expiry_date)
                                                <span class="{{ $document->expiry_date->isPast() ? 'text-danger' : 'text-success' }}">
                                                    {{ $document->expiry_date->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-success">Permanent</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($document->uploadedBy)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-light text-primary rounded">
                                                            {{ substr($document->uploadedBy->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div>{{ $document->uploadedBy->name }}</div>
                                                        <small class="text-muted">{{ $document->uploadedBy->role_display ?? 'User' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                         </td>
                                        <td>
                                            <x-document-status-badge :document="$document" :compact="false" :show-days="false" />
                                         </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-primary" 
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($document->file_path)
                                                    <a href="{{ route('documents.download', $document) }}" class="btn btn-outline-success" 
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->canEditDocument($document) && !$document->isArchived())
                                                    <a href="{{ route('documents.edit', $document) }}" class="btn btn-outline-warning" 
                                                    title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->canDeleteDocument($document))
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                                      class="d-inline" onsubmit="return confirm('Delete this document?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination - Moved outside card-body but inside card -->
                        @if($documents->hasPages())
                        <div class="card-footer bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="text-muted mb-2 mb-sm-0">
                                    Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
                                </div>
                                <div>
                                    {{ $documentTypes->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No documents found</h5>
                            <p class="text-muted">
                                @if(request()->anyFilled(['search', 'pillar', 'status']))
                                    Try adjusting your search criteria
                                @else
                                    Start by uploading your first document
                                @endif
                            </p>
                            <a href="{{ route('documents.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Upload Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection