@extends('layouts.app')

@section('title', 'Applicant Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-user-tie text-primary"></i> Applicant Details
                    </h1>
                    <p class="text-muted mb-0">{{ $applicant->full_name }}</p>
                </div>
                <div class="btn-group">
                    @if(auth()->user()->canEditApplicant($applicant))
                    <a href="{{ route('applicants.edit', $applicant) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    @endif
                    <a href="{{ route('applicants.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-id-card text-muted"></i> Applicant Information
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-primary">
                                        <i class="fas fa-user me-1"></i> Personal Information
                                    </h6>
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">Full Name</label>
                                        <p class="mb-0 fs-5">{{ $applicant->full_name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">Email</label>
                                        <p class="mb-0">{{ $applicant->email }}</p>
                                    </div>
                                    <div>
                                        <label class="form-label text-muted mb-1">Phone</label>
                                        <p class="mb-0">{{ $applicant->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-primary">
                                        <i class="fas fa-info-circle me-1"></i> Application Details
                                    </h6>
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">Status</label>
                                        <div>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'hired' => 'primary', 
                                                    'rejected' => 'danger',
                                                    'withdrawn' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$applicant->status] ?? 'secondary' }} fs-6">
                                                {{ ucfirst($applicant->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted mb-1">Created</label>
                                        <p class="mb-0">{{ $applicant->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <div>
                                        <label class="form-label text-muted mb-1">Last Updated</label>
                                        <p class="mb-0">{{ $applicant->updated_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Associated Documents -->
                    <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt text-muted"></i> Associated Documents
                                <span class="badge bg-secondary ms-2">{{ $applicant->documents->count() }}</span>
                            </h5>
                            <a href="{{ route('documents.create') }}?applicant_id={{ $applicant->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Document
                            </a>
                        </div>
                        
                        @if($applicant->documents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Title</th>
                                            <th>Type</th>
                                            <th>Document Date</th>
                                            <th>Expiry Date</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applicant->documents as $document)
                                        <tr>
                                            <td>
                                                <a href="{{ route('documents.show', $document) }}" class="text-decoration-none">
                                                    <strong>{{ $document->title }}</strong>
                                                </a>
                                                @if($document->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $document->documentType->name ?? 'N/A' }}</span>
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
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($document->file_path)
                                                        <a href="{{ route('documents.download', $document) }}" class="btn btn-outline-success" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading mb-1">No documents found</h6>
                                        <p class="mb-0">This applicant doesn't have any associated documents yet.</p>
                                        <a href="{{ route('documents.create') }}?applicant_id={{ $applicant->id }}" class="alert-link mt-2 d-inline-block">
                                            <i class="fas fa-plus me-1"></i> Add First Document
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection