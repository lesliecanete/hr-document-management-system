@extends('layouts.app')

@section('title', 'Applicants')

@section('content')
   <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-tie"></i> Applicants
                        </h4>
                        <a href="{{ route('applicants.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Applicant
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Applicants Table -->
                        @if(isset($applicants) && $applicants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applicants as $applicant)
                                        <tr>
                                            <td>
                                                <strong>{{ $applicant->full_name }}</strong>
                                            </td>
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
                                                <div class="btn-group">
                                                    <a href="{{ route('applicants.show', $applicant->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('applicants.edit', $applicant->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('applicants.destroy', $applicant->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this applicant?')"
                                                                title="Delete">
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

                            <!-- Pagination -->
                            {{ $applicants->links() }}
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No applicants found.
                                <a href="{{ route('applicants.create') }}" class="alert-link">Add your first applicant</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
