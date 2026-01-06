@extends('layouts.app')

@section('title', 'HR Pillars')

@section('content')

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-layer-group"></i> HR Pillars
                        </h4>
                        <a href="{{ route('pillars.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add HR Pillar
                        </a>
                        
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- HR Pillars Table -->
                        @if(isset($pillars) && $pillars->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Document Types</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pillars as $pillar)
                                        <tr>
                                            <td>
                                                <strong>{{ $pillar->name }}</strong>
                                            </td>
                                          
                                            <td>
                                                @if($pillar->description)
                                                    {{ Str::limit($pillar->description, 50) }}
                                                @else
                                                    <span class="text-muted">No description</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $pillar->documentTypes()->count() }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $pillar->is_active ? 'success' : 'secondary' }}">
                                                    {{ $pillar->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('pillars.edit', $pillar->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('pillars.destroy', $pillar->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this HR pillar?')"
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
                            @if($pillars->hasPages())
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Showing {{ $pillars->firstItem() }} to {{ $pillars->lastItem() }} of {{ $pillars->total() }} results
                                    </div>
                                    {{ $pillars->links() }}
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No HR pillars found.
                                @if(Route::has('settings.pillars.create'))
                                    <a href="{{ route('settings.pillars.create') }}" class="alert-link">Add your first HR pillar</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
