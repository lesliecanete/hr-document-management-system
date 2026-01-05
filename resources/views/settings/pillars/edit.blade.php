@extends('layouts.app')

@section('title', 'Edit HR Pillar')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit"></i> Edit HR Pillar
                        </h4>
                        <a href="{{ route('pillars.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Pillars
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('pillars.update', $pillar->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label required-field">Pillar Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $pillar->name) }}" 
                                       placeholder="Enter pillar name (e.g., Recruitment & Selection)" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> The display name for this HR pillar
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Enter a brief description of this HR pillar (optional)">{{ old('description', $pillar->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-align-left"></i> Describe the purpose and scope of this HR pillar
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required-field">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_active" id="active" 
                                           value="1" {{ old('is_active', $pillar->is_active) ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="active">
                                        <span class="badge bg-success">Active</span> - Pillar will be available for use
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_active" id="inactive" 
                                           value="0" {{ !old('is_active', $pillar->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactive">
                                        <span class="badge bg-secondary">Inactive</span> - Pillar will be hidden from selection
                                    </label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                                <div>
                                    <a href="{{ route('pillars.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                                <div>
                                    <button type="reset" class="btn btn-outline-danger me-2">
                                        <i class="fas fa-redo"></i> Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update HR Pillar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Pillar Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle"></i> Pillar Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Created:</small>
                                <p class="mb-2">{{ $pillar->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Last Updated:</small>
                                <p class="mb-0">{{ $pillar->updated_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            
            if (!name.trim()) {
                e.preventDefault();
                alert('Please enter a pillar name.');
                return;
            }
        });
    </script>
@endsection
