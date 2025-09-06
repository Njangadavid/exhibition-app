<x-event-layout :event="$event">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="bi bi-pencil me-2"></i>
            {{ __('Edit Event') }}: {{ $event->name }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
                <i class="bi bi-eye me-2"></i>
                View Event
            </a>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Events
            </a>
        </div>
    </div>

    <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Event Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $event->name) }}" 
                                   placeholder="{{ __('Enter event name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-text-paragraph me-2"></i>
                                Event Description *
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4"
                                class="form-control"
                                placeholder="Describe your event in detail"
                                required
                            >{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Logo Display -->
                        @if($event->logo)
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-image me-2"></i>
                                    Current Logo
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ Storage::url($event->logo) }}" alt="Current logo" class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
                                    <div class="text-muted small">
                                        Current event logo
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Logo Upload -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="bi bi-image me-2"></i>
                                {{ $event->logo ? 'Update Logo' : 'Event Logo' }}
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input 
                                    type="file" 
                                    id="logo" 
                                    name="logo" 
                                    accept="image/*"
                                    class="form-control"
                                />
                                <div class="text-muted small">
                                    PNG, JPG, GIF up to 2MB
                                    @if($event->logo)
                                        <br>Leave empty to keep current logo
                                    @endif
                                </div>
                            </div>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="bi bi-flag me-2"></i>
                                Event Status *
                            </label>
                            <select 
                                id="status" 
                                name="status"
                                class="form-select"
                                required
                            >
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $event->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date Range -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    Start Date & Time *
                                </label>
                                <input 
                                    type="datetime-local" 
                                    id="start_date" 
                                    name="start_date" 
                                    value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}"
                                    class="form-control"
                                    required
                                />
                                @error('start_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    End Date & Time *
                                </label>
                                <input 
                                    type="datetime-local" 
                                    id="end_date" 
                                    name="end_date" 
                                    value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}"
                                    class="form-control"
                                    required
                                />
                                @error('end_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Assigned Users -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-people me-2"></i>
                                Assign Users to Event
                            </label>
                            @if(auth()->user()->hasRole('admin'))
                                <p class="text-muted small mb-3">As an administrator, you can manage all user assignments including the event owner.</p>
                            @else
                                <p class="text-muted small mb-3">Select which users can view and manage this event. The event owner cannot be removed.</p>
                            @endif
                            
                            <div class="row">
                                @foreach($users as $user)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        @if($user->id === $event->owner_id)
                                            @if(auth()->user()->hasRole('admin'))
                                                <!-- Event Owner - Admin can manage -->
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    name="assigned_users[]" 
                                                    value="{{ $user->id }}" 
                                                    id="user_{{ $user->id }}"
                                                    {{ in_array($user->id, old('assigned_users', $event->users->pluck('id')->toArray())) ? 'checked' : '' }}
                                                />
                                                <label class="form-check-label" for="user_{{ $user->id }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-crown text-warning"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $user->name }} <span class="badge bg-warning ms-1">Owner (Admin can remove)</span></div>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            @else
                                                <!-- Event Owner - Non-admin cannot manage -->
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    checked 
                                                    disabled
                                                />
                                                <label class="form-check-label">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-crown text-success"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $user->name }} <span class="badge bg-success ms-1">Owner</span></div>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endif
                                        @else
                                            <!-- Regular User -->
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="assigned_users[]" 
                                                value="{{ $user->id }}" 
                                                id="user_{{ $user->id }}"
                                                {{ in_array($user->id, old('assigned_users', $event->users->pluck('id')->toArray())) ? 'checked' : '' }}
                                            />
                                            <label class="form-check-label" for="user_{{ $user->id }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-person text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $user->name }}</div>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @error('assigned_users')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>
                                Update Event
                            </button>
                        </div>
                    </form>
                    @if(auth()->user()->hasRole('admin'))
        <div class="card mt-4" id="ownership-transfer">
            <div class="card-header bg-warning bg-opacity-10">
                <h6 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Transfer Event Ownership
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">As an administrator, you can transfer ownership of this event to another user.</p>
                
                <form method="POST" action="{{ route('events.transfer-ownership', $event) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <select name="new_owner_id" class="form-select" required>
                                <option value="">Select new owner...</option>
                                @foreach($users as $user)
                                    @if($user->id !== $event->owner_id)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to transfer ownership? This action cannot be undone.')">
                                <i class="bi bi-arrow-right-circle me-2"></i>
                                Transfer Ownership
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
                </div>
            </div>
        </div>

        <!-- Ownership Transfer (Admin Only) - Outside main form -->
        
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action*="events.update"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button clicked');
            
            // Check all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    console.log('Empty required field:', field.name);
                    field.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
        });
    });
    </script>
    @endpush

</x-event-layout>
