@extends('layouts.app')

@section('title', 'Applicant Details')

@section('content')

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-tie"></i> {{ $applicant->full_name }}
                        </h4>
                        <div class="btn-group">
                            <a href="{{ route('applicants.edit', $applicant) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('applicants.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Personal Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Full Name:</th>
                                        <td>{{ $applicant->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $applicant->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $applicant->phone ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Application Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Status:</th>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'hired' => 'primary', 
                                                    'rejected' => 'danger',
                                                    'withdrawn' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$applicant->status] ?? 'secondary' }}">
                                                {{ ucfirst($applicant->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $applicant->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $applicant->updated_at->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Associated Documents -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Associated Documents</h6>
                                <a href="{{ route('documents.create') }}?applicant_id={{ $applicant->id }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Document
                                </a>
                            </div>
                            @if($applicant->documents->count() > 0)
                                <div class="list-group">
                                    @foreach($applicant->documents as $document)
                                    <a href="{{ route('documents.show', $document->id) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $document->title }}</h6>
                                            <small>{{ $document->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 text-muted small">
                                            {{ $document->documentType->name ?? 'N/A' }} • 
                                            {{ $document->document_date->format('M d, Y') }}
                                            @if($document->expiry_date)
                                                • Expires: {{ $document->expiry_date->format('M d, Y') }}
                                            @endif
                                        </p>
                                    </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No documents associated with this applicant.
                                    <a href="{{ route('documents.create') }}?applicant_id={{ $applicant->id }}" class="alert-link">Add a document</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
