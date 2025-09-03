<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
                        <li class="breadcrumb-item active">Role Management</li>
                    </ol>
                </div>
                <h4 class="page-title">Role & Permission Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($roles as $role)
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'organizer' ? 'warning' : 'secondary') }} me-2">
                                {{ $role->display_name }}
                            </span>
                        </h5>
                        <small class="text-muted">{{ $role->users->count() }} users</small>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">{{ $role->description }}</p>
                    
                    <form method="POST" action="{{ route('admin.users.roles.permissions', $role) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            @foreach($permissions as $category => $categoryPermissions)
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted border-bottom pb-1">{{ ucwords(str_replace('_', ' ', $category)) }}</h6>
                                @foreach($categoryPermissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="permission_{{ $role->id }}_{{ $permission->id }}" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $role->id }}_{{ $permission->id }}">
                                        {{ $permission->display_name }}
                                        @if($permission->description)
                                            <br><small class="text-muted">{{ $permission->description }}</small>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-check-circle me-1"></i>Update Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Permission Categories Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permission Categories Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($permissions as $category => $categoryPermissions)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        @switch($category)
                                            @case('user_management')
                                                <i class="bi bi-people fs-1 text-primary"></i>
                                                @break
                                            @case('event_management')
                                                <i class="bi bi-calendar-event fs-1 text-success"></i>
                                                @break
                                            @case('floorplan_management')
                                                <i class="bi bi-grid-3x3-gap fs-1 text-info"></i>
                                                @break
                                            @case('booking_management')
                                                <i class="bi bi-bookmark-check fs-1 text-warning"></i>
                                                @break
                                            @case('payment_management')
                                                <i class="bi bi-credit-card fs-1 text-danger"></i>
                                                @break
                                            @case('form_management')
                                                <i class="bi bi-file-text fs-1 text-secondary"></i>
                                                @break
                                            @case('email_management')
                                                <i class="bi bi-envelope fs-1 text-dark"></i>
                                                @break
                                            @case('system_administration')
                                                <i class="bi bi-gear fs-1 text-primary"></i>
                                                @break
                                            @default
                                                <i class="bi bi-shield-check fs-1 text-muted"></i>
                                        @endswitch
                                    </div>
                                    <h6 class="card-title">{{ ucwords(str_replace('_', ' ', $category)) }}</h6>
                                    <p class="text-muted small">{{ $categoryPermissions->count() }} permissions</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add some interactivity to the role management
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for permission updates
    const forms = document.querySelectorAll('form[action*="roles.permissions"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const roleName = this.closest('.card').querySelector('.badge').textContent.trim();
            if (!confirm(`Are you sure you want to update permissions for the "${roleName}" role?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
</x-app-layout>
