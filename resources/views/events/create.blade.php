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
                            <label for="title" class="form-label">
                                <i class="bi bi-type me-2"></i>
                                Event Title *
                            </label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}"
                                class="form-control"
                                placeholder="Enter event title"
                                required
                            />
                            @error('title')
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
