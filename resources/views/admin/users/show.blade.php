<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
                        <li class="breadcrumb-item active">User Details</li>
                    </ol>
                </div>
                <h4 class="page-title">User Details: {{ $user->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">User Information</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i>Edit User
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">User ID</label>
                                <p class="form-control-plaintext">{{ $user->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    @if($user->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Verified</label>
                                <p class="form-control-plaintext">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Yes</span>
                                        <small class="text-muted">({{ $user->email_verified_at->format('M d, Y H:i') }})</small>
                                    @else
                                        <span class="badge bg-warning">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Login</label>
                                <p class="form-control-plaintext">
                                    @if($user->last_login_at ?? false)
                                        {{ $user->last_login_at->format('M d, Y H:i') }}
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created</label>
                                <p class="form-control-plaintext">{{ $user->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="form-control-plaintext">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Roles & Permissions</h5>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'organizer' ? 'warning' : 'secondary') }} me-2 fs-6">
                                    {{ $role->display_name }}
                                </span>
                                <small class="text-muted">{{ $role->description }}</small>
                            </div>
                            
                            @if($role->permissions->count() > 0)
                                <div class="row">
                                    @foreach($role->permissions->groupBy('category') as $category => $permissions)
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">{{ ucwords(str_replace('_', ' ', $category)) }}</h6>
                                        <ul class="list-unstyled">
                                            @foreach($permissions as $permission)
                                            <li class="small text-muted">• {{ $permission->display_name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small">No permissions assigned to this role.</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-shield-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Roles Assigned</h5>
                            <p class="text-muted">This user has no roles assigned and cannot access any protected features.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit User
                        </a>
                        
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100"
                                        onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                                    <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }} me-1"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="bi bi-trash me-1"></i>Delete User
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Users
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $user->roles->count() }}</h4>
                                <small class="text-muted">Roles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $user->permissions()->count() }}</h4>
                            <small class="text-muted">Permissions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
