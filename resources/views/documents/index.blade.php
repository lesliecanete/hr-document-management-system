<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents - HR Document Management</title>
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
                        <a class="nav-link active" href="{{ route('documents.index') }}">
                            <i class="fas fa-file-alt"></i> Documents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('applicants.index') }}">
                            <i class="fas fa-users"></i> Applicants
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="fas fa-user-cog"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('document-types.index') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        @endif
                    @endauth
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
                <i class="fas fa-file-alt"></i> Documents
            </h1>
            <a href="{{ route('documents.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Document
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Search & Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search documents...">
                    </div>
                    <div class="col-md-3">
                        <label for="pillar" class="form-label">HR Pillar</label>
                        <select class="form-select" id="pillar" name="pillar">
                            <option value="">All Pillars</option>
                            @foreach($pillars as $pillar)
                                <option value="{{ $pillar->slug }}" {{ request('pillar') == $pillar->slug ? 'selected' : '' }}>
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
                            <option value="expiring_soon" {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Documents List</h5>
                <span class="badge bg-secondary">{{ $documents->total() }} documents</span>
            </div>
            <div class="card-body">
                @if($documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Applicant</th>
                                    <th>Document Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <strong>{{ $document->title }}</strong>
                                        @if($document->description)
                                            <br><small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $document->documentType->name }}</span>
                                        <br><small class="text-muted">{{ $document->documentType->pillar->name }}</small>
                                    </td>
                                    <td>
                                        @if($document->applicant)
                                            <a href="{{ route('applicants.show', $document->applicant->id) }}">
                                                {{ $document->applicant->full_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $document->document_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="{{ $document->isExpiringSoon() ? 'text-warning' : 'text-muted' }}">
                                            {{ $document->expiry_date->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($document->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($document->status == 'expiring_soon')
                                            <span class="badge bg-warning">Expiring Soon</span>
                                        @else
                                            <span class="badge bg-secondary">Archived</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('documents.download', $document) }}" 
                                               class="btn btn-outline-success" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('documents.show', $document) }}" 
                                               class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('documents.edit', $document) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this document?')">
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
                        </div>
                        {{ $documents->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h4>No documents found</h4>
                        <p class="text-muted">
                            @if(request()->anyFilled(['search', 'pillar', 'status']))
                                Try adjusting your search criteria
                            @else
                                Get started by uploading your first document
                            @endif
                        </p>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Document
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>