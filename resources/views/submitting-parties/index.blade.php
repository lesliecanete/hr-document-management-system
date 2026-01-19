@extends('layouts.app')

@section('title', 'Submitting Parties')

@section('content')
   <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1">
                            <i class="fas fa-user-tie text-primary"></i> Submitting Parties
                        </h1>
                        <p class="text-muted mb-0">Manage Submitting Parties</p>
                    </div>
                    <a href="{{ route('submitting-parties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Submitting Party
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list text-muted"></i> Submitting Party List
                            <span class="badge bg-secondary ms-2">{{ $applicants->total() ?? 0 }} applicants</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($applicants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Added By</th> 
                                            <th>Date Added</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applicants as $applicant)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $applicant->full_name }}</strong>
                                            </td>
                                            <td>{{ $applicant->position }}</td>
                                            <td>{{ $applicant->email }}</td>
                                            <td>{{ $applicant->phone ?? 'N/A' }}</td>
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
                                            <td>
                                                @if($applicant->addedByUser)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2">
                                                            <div class="avatar-title bg-light text-primary rounded">
                                                                {{ substr($applicant->addedByUser->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div>{{ $applicant->addedByUser->name }}</div>
                                                            <small class="text-muted">{{ $applicant->addedByUser->role_display }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                            <td>{{ $applicant->created_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('submitting-parties.show', $applicant) }}" 
                                                       class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(auth()->user()->canEditApplicant($applicant))
                                                    <a href="{{ route('submitting-parties.edit', $applicant) }}" 
                                                       class="btn btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endif
                                                    @if(auth()->user()->canDeleteApplicant($applicant))
                                                    <form action="{{ route('submitting-parties.destroy', $applicant) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Delete this applicant?')">
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
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-user-tie fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted">No applicants found</h5>
                                <p class="text-muted">Start by adding your first applicant</p>
                                <a href="{{ route('submitting-parties.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-1"></i> Add First Applicant
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($applicants->hasPages())
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of {{ $applicants->total() }} applicants
                            </div>
                            <div>
                                {{ $applicants->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection