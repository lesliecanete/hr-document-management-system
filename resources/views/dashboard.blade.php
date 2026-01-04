@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h1 class="mb-4">Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-sm font-weight-bold text-uppercase mb-1">Total Documents</div>
                            <div class="h4 mb-0">{{ $stats['total_documents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-sm font-weight-bold text-uppercase mb-1">Active Documents</div>
                            <div class="h4 mb-0">{{ $stats['active_documents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-sm font-weight-bold text-uppercase mb-1">Expiring Soon</div>
                            <div class="h4 mb-0">{{ $stats['expiring_soon'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-sm font-weight-bold text-uppercase mb-1">Total Applicants</div>
                            <div class="h4 mb-0">{{ $stats['total_applicants'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HR Pillars Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">HR Management Pillars</h5>
                </div>
                <div class="card-body">
                    @if(isset($pillars) && $pillars->count() > 0)
                        <div class="row">
                            @foreach($pillars as $pillar)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $pillar->name }}</h6>
                                        <p class="card-text text-muted small">{{ $pillar->description ?? 'No description' }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="h5 mb-0">{{ $pillar->documents_count ?? 0 }}</span>
                                            <a href="{{ route('documents.index', ['pillar' => $pillar->slug]) }}" 
                                               class="btn btn-sm btn-outline-primary">View Documents</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No HR pillars configured yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Documents & Expiring Documents -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Documents</h5>
                </div>
                <div class="card-body">
                    @if($recentDocuments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentDocuments as $document)
                            <a href="{{ route('documents.show', $document->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $document->title }}</h6>
                                    <small>{{ $document->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted small">
                                    @if($document->documentType)
                                        {{ $document->documentType->name }} 
                                    @endif
                                    @if($document->applicant)
                                        • {{ $document->applicant->full_name }}
                                    @endif
                                </p>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No documents uploaded yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">Documents Expiring Soon</h5>
                </div>
                <div class="card-body">
                    @if($expiringDocuments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($expiringDocuments as $document)
                            <a href="{{ route('documents.show', $document->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $document->title }}</h6>
                                    <small class="text-danger">
                                        Expires: {{ $document->expiry_date->format('M d, Y') }}
                                    </small>
                                </div>
                                <p class="mb-1 text-muted small">
                                    @if($document->documentType)
                                        {{ $document->documentType->name }} 
                                    @endif
                                    @if($document->applicant)
                                        • {{ $document->applicant->full_name }}
                                    @endif
                                </p>
                            </a>
                            @endforeach
                        </div>
                        
                        <form action="{{ route('documents.archive') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-archive"></i> Archive All Expired Documents
                            </button>
                        </form>
                    @else
                        <p class="text-muted">No documents expiring soon.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('documents.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-upload"></i> Upload Document
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('documents.index') }}" class="btn btn-success w-100">
                                <i class="fas fa-search"></i> Search Documents
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('applicants.index') }}" class="btn btn-info w-100">
                                <i class="fas fa-user-tie"></i> Manage Applicants
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pillars.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
