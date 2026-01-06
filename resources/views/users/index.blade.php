@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-cog"></i> User Management
            </h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">System Users</h5>
                <span class="badge bg-secondary">{{ $users->total() }} users</span>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id == auth()->id())
                                            <span class="badge bg-info">You</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-danger">Administrator</span>
                                        @elseif($user->role == 'hr_manager')
                                            <span class="badge bg-warning">HR Manager</span>
                                        @else
                                            <span class="badge bg-info">HR Staff</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id != auth()->id())
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                        </div>
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4>No users found</h4>
                        <p class="text-muted">Get started by adding your first user</p>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Add User
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Roles Legend -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">User Roles & Permissions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-danger me-2">Administrator</span>
                            <small>Full system access</small>
                        </div>
                        <ul class="small text-muted">
                            <li>Manage all documents</li>
                            <li>User management</li>
                            <li>System settings</li>
                            <li>All HR pillars</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-warning me-2">HR Manager</span>
                            <small>Manager access</small>
                        </div>
                        <ul class="small text-muted">
                            <li>Manage documents</li>
                            <li>View all HR pillars</li>
                            <li>Applicant management</li>
                            <li>Cannot manage users</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-info me-2">HR Staff</span>
                            <small>Standard access</small>
                        </div>
                        <ul class="small text-muted">
                            <li>Upload documents</li>
                            <li>View assigned pillars</li>
                            <li>Basic document management</li>
                            <li>Limited access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection