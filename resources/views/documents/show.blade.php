@extends('layouts.app')

@section('title', 'Document Details')

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
                <i class="fas fa-file-alt"></i> Document Details
            </h1>
            <div class="btn-group">
               <!-- <a href="{{ route('documents.download', $document) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download
                </a>
                @if(auth()->user()->canEditDocument($document))
                <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endif -->
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $document->title }}</h5>
                    </div>
                    <div class="card-body">
                        @if($document->description)
                            <div class="mb-3">
                                <strong>Description:</strong>
                                <p class="mb-0">{{ $document->description }}</p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Document Type:</th>
                                        <td>
                                            {{ $document->documentType->name ?? 'N/A' }}
                                            @if($document->documentType && $document->documentType->pillar)
                                                <br><small class="text-muted">{{ $document->documentType->pillar->name }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Document Date:</th>
                                        <td>{{ $document->document_date->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry Date:</th>
                                        <td>
                                            @if($document->expiry_date)
                                                <span class="{{ $document->expiry_date->isPast() ? 'text-danger' : 'text-success' }}">
                                                    {{ $document->expiry_date->format('F d, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Status:</th>
                                        <td>
                                               <x-document-status-badge :document="$document" :compact="false" :show-days="true" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>File Name:</th>
                                        <td> <i class="{{ $document->file_icon }} fa-2x me-3"></i> {{ $document->file_name }}   
                                            <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>File Type:</th>
                                        <td>{{ $document->file_type }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Applicant Information Section - ADDED HERE -->
                        @if($document->applicant)
                        <div class="mt-4">
                            <h6>Submitting Party Information</h6>
                           <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Submitting Party Name:</th>
                                    <td>
                                        <a href="{{ route('submitting-parties.show', $document->applicant->id) }}" class="text-decoration-none">
                                            <strong>{{ $document->applicant->full_name }}</strong>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Contact Information:</th>
                                    <td>
                                        <div class="d-md-flex gap-4">
                                            <div class="flex-fill">
                                                <div class="mb-2">
                                                    <i class="fas fa-envelope text-muted me-2"></i>
                                                    <strong>Email:</strong><br>
                                                    <a href="mailto:{{ $document->applicant->email }}" class="text-decoration-none">
                                                        {{ $document->applicant->email }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="mb-2">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    <strong>Phone:</strong><br>
                                                    {{ $document->applicant->phone ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Details:</th>
                                    <td>
                                        <div class="d-md-flex gap-4">
                                            <div class="flex-fill">
                                                <div class="mb-2">
                                                    <i class="fas fa-briefcase text-muted me-2"></i>
                                                    <strong>Position:</strong><br>
                                                    {{ $document->applicant->position ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="mb-2">
                                                    <i class="fas fa-info-circle text-muted me-2"></i>
                                                    <strong>Status:</strong><br>
                                                    @php
                                                        $statusColors = [
                                                            'active' => 'success',
                                                            'hired' => 'primary', 
                                                            'rejected' => 'danger',
                                                            'withdrawn' => 'secondary'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColors[$document->applicant->status] ?? 'secondary' }}">
                                                        {{ ucfirst($document->applicant->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @else
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No applicant associated with this document.
                            </div>
                        </div>
                        @endif

                        @if($document->notes)
                        <div class="mt-3">
                            <h6>Notes</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $document->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
               
            </div>

            <div class="col-md-4">
                  <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Document Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                          
                            <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Details
                            </a>
                           
                            @if(auth()->user()->canDeleteDocument($document))
                            <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> Delete Document
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-qrcode"></i> Document QR Code
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- QR Code Image -->
                        <div class="flex-shrink-0">
                            <img src="{{ route('documents.qrcode', $document) }}" 
                                alt="QR Code for {{ $document->file_name }}"
                                class="img-fluid rounded border shadow-sm"
                                style="max-width: 150px;">
                        </div>
                        
                       <!-- File Information -->
                        <div class="flex-grow-1 mt-3">
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Scanning this QR code will open/download: 
                                <strong>{{ $document->file_name }}</strong>
                            </div>
                                                       
                            <!-- Test Links -->
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" 
                                    target="_blank"
                                    class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span>Open File</span>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('documents.download', $document) }}" 
                                    class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-download"></i>
                                        <span>Download File</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- QR Download Links -->
                            <div class="mt-3">
                                <h6 class="mb-2">Download QR Code:</h6>
                                <div class="btn-group w-100">
                                    <a href="{{ route('documents.qrcode', $document) }}?format=svg" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-1"
                                    download="qr-{{ $document->id }}-{{ Str::slug($document->title) }}.svg">
                                        <i class="fas fa-download"></i>
                                        <span>SVG</span>
                                    </a>
                                    <a href="{{ route('documents.qrcode', $document) }}?format=png" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-1"
                                    download="qr-{{ $document->id }}-{{ Str::slug($document->title) }}.png">
                                        <i class="fas fa-download"></i>
                                        <span>PNG</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>                          
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Document Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Created On:</th>
                                <td>{{ $document->created_at->format('F d, Y g:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $document->updated_at->format('F d, Y g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($document->expiry_date)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Retention Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Retention Period:</strong>
                            <br>{{ $document->documentType->retention_years ?? 'N/A' }} years
                        </div>  
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection