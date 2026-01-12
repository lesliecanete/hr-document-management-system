@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="avatar-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px; border-radius: 50%;">
                            <span class="text-white display-4">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                        @if(Auth::user()->position)
                            <p class="text-muted small">{{ Auth::user()->position }}</p>
                        @endif
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-key me-2"></i> Change Password
                        </a>
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Info</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Role</small>
                        <span class="badge bg-{{ Auth::user()->role == 'admin' ? 'danger' : 'primary' }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Member Since</small>
                        <p class="mb-0">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                    </div>
                    @if(Auth::user()->last_login_at)
                    <div>
                        <small class="text-muted d-block">Last Login</small>
                        <p class="mb-0">{{ Auth::user()->last_login_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profile Details</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Full Name</label>
                                <div class="border-bottom pb-2">
                                    <strong>{{ Auth::user()->name }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <div class="border-bottom pb-2">
                                    <strong>{{ Auth::user()->email }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="+1 (123) 456-7890">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Section -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentDocuments = Auth::user()->documents()->latest()->take(3)->get();
                    @endphp
                    
                    @if($recentDocuments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentDocuments as $document)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('documents.show', $document) }}" class="text-decoration-none">
                                            {{ $document->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $document->documentType->name ?? 'N/A' }} • 
                                        {{ $document->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $document->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($document->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @if(Auth::user()->documents()->count() > 3)
                        <div class="text-center mt-3">
                            <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-secondary">
                                View All Documents ({{ Auth::user()->documents()->count() }})
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No documents uploaded yet.</p>
                            <a href="{{ route('documents.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
</style>
@endsection