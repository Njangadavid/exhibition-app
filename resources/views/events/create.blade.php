<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-plus me-2"></i>
                {{ __('Create New Event') }}
            </h2>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-type me-2"></i>
                                Event Name *
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                class="form-control"
                                placeholder="Enter event name"
                                required
                            />
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo Upload -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="bi bi-image me-2"></i>
                                Event Logo
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
                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
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
                                    value="{{ old('start_date') }}"
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
                                    value="{{ old('end_date') }}"
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
                            <p class="text-muted small mb-3">Select which users can view and manage this event. You will automatically be assigned as the event owner.</p>
                            
                            <div class="row">
                                @foreach($users as $user)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="assigned_users[]" 
                                            value="{{ $user->id }}" 
                                            id="user_{{ $user->id }}"
                                            {{ in_array($user->id, old('assigned_users', [])) ? 'checked' : '' }}
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
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
