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
                                   value="{{ request('search') }}" placeholder="Title, description, applicant...">
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
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
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
                        @if(request()->anyFilled(['search', 'pillar', 'status']))
                            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Type / Pillar</th>
                                        <th>Applicant</th>
                                        <th>Document Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                    <tr>
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
                                                <span class="badge bg-info">{{ $document->documentType->name ?? 'N/A' }}</span>
                                                <br>
                                                <small class="text-muted">{{ $document->documentType->pillar->name ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($document->applicant)
                                                <a href="{{ route('applicants.show', $document->applicant) }}" class="text-primary">
                                                    {{ $document->applicant->full_name ?? $document->applicant->first_name }}
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
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($document->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($document->status == 'expired')
                                                <span class="badge bg-warning">Expired</span>
                                            @elseif($document->status == 'archived')
                                                <span class="badge bg-secondary">Archived</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $document->status }}</span>
                                            @endif
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
                                                <a href="{{ route('documents.edit', $document) }}" class="btn btn-outline-warning" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                                      class="d-inline" onsubmit="return confirm('Delete this document?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                @if($documents->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
                        </div>
                        <div>
                            {{ $documents->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection