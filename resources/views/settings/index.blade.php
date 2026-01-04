@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container mt-4">
    <h1><i class="fas fa-cog"></i> System Settings</h1>
    
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Document Types</h5>
                    <p class="card-text">Manage document types and retention policies</p>
                    <a href="{{ route('document-types.index') }}" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">Manage system users and permissions</p>
                    <a href="{{ route('users.index') }}" class="btn btn-success">Manage</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-database fa-3x text-info mb-3"></i>
                    <h5 class="card-title">System Info</h5>
                    <p class="card-text">View system information and logs</p>
                    <button class="btn btn-info" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection