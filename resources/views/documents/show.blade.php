<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->title }} - HR Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-file-contract"></i> HR Document Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documents.index') }}">
                            <i class="fas fa-file-alt"></i> Documents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('applicants.index') }}">
                            <i class="fas fa-users"></i> Applicants
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

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
                <a href="{{ route('documents.download', $document) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download
                </a>
                <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
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
                                                <span class="{{ $document->isExpiringSoon() ? 'text-warning fw-bold' : '' }}">
                                                    {{ $document->expiry_date->format('F d, Y') }}
                                                </span>
                                                @if($document->isExpiringSoon())
                                                    <br><small class="text-warning">Expiring soon!</small>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
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
                                            @if($document->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($document->status == 'expiring_soon')
                                                <span class="badge bg-warning">Expiring Soon</span>
                                            @else
                                                <span class="badge bg-secondary">Archived</span>
                                            @endif
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
                            <h6>Applicant Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Applicant Name:</th>
                                    <td>
                                        <a href="{{ route('applicants.show', $document->applicant->id) }}">
                                            {{ $document->applicant->full_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $document->applicant->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $document->applicant->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
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
                            <a href="{{ route('documents.download', $document) }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Download Document
                            </a>
                            <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Details
                            </a>
                            <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> Delete Document
                                </button>
                            </form>
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
                        <div class="mb-2">
                            <strong>Days until expiry:</strong>
                            <br>
                            @php
                                $daysUntilExpiry = \Carbon\Carbon::now()->diffInDays($document->expiry_date, false);
                            @endphp
                            @if($daysUntilExpiry > 0)
                                <span class="text-success">{{ $daysUntilExpiry }} days</span>
                            @else
                                <span class="text-danger">Expired {{ abs($daysUntilExpiry) }} days ago</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>