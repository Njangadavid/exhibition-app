<x-event-layout :event="$event">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="bi bi-pencil me-2"></i>
            Edit Form - {{ $formBuilder->name }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('events.form-builders.show', [$event, $formBuilder]) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Form
            </a>
        </div>
    </div>

    <div class="card mb-4">
            <!-- Event Header -->
                <div class="position-relative">
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 150px;">
                        @if($event->logo)
                            <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="bi bi-calendar-event text-white" style="font-size: 3rem;"></i>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge 
                                @if($event->status === 'active') bg-success
                                @elseif($event->status === 'published') bg-primary
                                @elseif($event->status === 'completed') bg-info
                                @elseif($event->status === 'cancelled') bg-danger
                                @else bg-secondary @endif fs-6">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-3">
                        <h1 class="h4 mb-1">{{ $event->name }}</h1>
                        <p class="mb-0 opacity-75 small">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('events.form-builders.update', [$event, $formBuilder]) }}" method="POST" id="formBuilderForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Form Settings -->
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-gear me-2"></i>Form Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Form Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           value="{{ $formBuilder->name }}" placeholder="e.g., Participant Registration">
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Form Type *</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Form Type</option>
                                        <option value="member_registration" {{ $formBuilder->type === 'member_registration' ? 'selected' : '' }}>Member Registration</option>
                                        <option value="exhibitor_registration" {{ $formBuilder->type === 'exhibitor_registration' ? 'selected' : '' }}>Exhibitor Registration</option>
                                        <option value="speaker_registration" {{ $formBuilder->type === 'speaker_registration' ? 'selected' : '' }}>Speaker Registration</option>
                                        <option value="delegate_registration" {{ $formBuilder->type === 'delegate_registration' ? 'selected' : '' }}>Delegate Registration</option>
                                        <option value="general" {{ $formBuilder->type === 'general' ? 'selected' : '' }}>General Form</option>
                                    </select>
                                    <div class="form-text">Choose the purpose of this form</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                              placeholder="Describe the purpose of this form">{{ $formBuilder->description }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" {{ $formBuilder->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ $formBuilder->status === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ $formBuilder->status === 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="submit_button_text" class="form-label">Submit Button Text</label>
                                    <input type="text" class="form-control" id="submit_button_text" name="submit_button_text" 
                                           value="{{ $formBuilder->submit_button_text }}" placeholder="Submit Registration">
                                </div>

                                <div class="mb-3">
                                    <label for="theme_color" class="form-label">Theme Color</label>
                                    <input type="color" class="form-control form-control-color" id="theme_color" name="theme_color" 
                                           value="{{ $formBuilder->theme_color }}" title="Choose theme color">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allow_multiple_submissions" 
                                               name="allow_multiple_submissions" value="1" {{ $formBuilder->allow_multiple_submissions ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_multiple_submissions">
                                            Allow multiple submissions per person
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="require_login" 
                                               name="require_login" value="1" {{ $formBuilder->require_login ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_login">
                                            Require user login to submit
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_confirmation_email" 
                                               name="send_confirmation_email" value="1" {{ $formBuilder->send_confirmation_email ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_confirmation_email">
                                            Send confirmation email
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirmation_message" class="form-label">Confirmation Message</label>
                                    <textarea class="form-control" id="confirmation_message" name="confirmation_message" rows="3"
                                              placeholder="Thank you for your registration!">{{ $formBuilder->confirmation_message }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="redirect_url" class="form-label">Redirect URL (Optional)</label>
                                    <input type="url" class="form-control" id="redirect_url" name="redirect_url" 
                                           value="{{ $formBuilder->redirect_url }}" placeholder="https://example.com/thank-you">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Update Form Settings
                                    </button>
                                    <a href="{{ route('events.form-builders.design', [$event, $formBuilder]) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square me-2"></i>Design Form
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Preview -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-eye me-2"></i>Form Preview
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('events.form-builders.design', [$event, $formBuilder]) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil-square me-2"></i>Edit Form
                                    </a>
                                    <a href="{{ route('events.form-builders.preview', [$event, $formBuilder]) }}" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="bi bi-eye-fill me-2"></i>Full Preview
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($formBuilder->fields->count() > 0)
                                    <div class="form-preview" style="max-width: 800px; margin: 0 auto;">
                                        <form class="needs-validation" novalidate>
                                            @php
                                                $currentSection = null;
                                                $sectionFields = collect();
                                            @endphp
                                            
                                            @foreach($formBuilder->fields as $field)
                                                @if($field->type === 'section')
                                                    @if($currentSection && $sectionFields->count() > 0)
                                                        <!-- Render previous section -->
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0">{{ $currentSection->label }}</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach($sectionFields as $sectionField)
                                                                        @include('form-builders.partials.field-preview', ['field' => $sectionField])
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php $sectionFields = collect(); @endphp
                                                    @endif
                                                    @php $currentSection = $field; @endphp
                                                @else
                                                    @if($currentSection)
                                                        @php $sectionFields->push($field); @endphp
                                                    @else
                                                        <!-- Fields outside sections -->
                                                        <div class="row">
                                                            @include('form-builders.partials.field-preview', ['field' => $field])
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                            
                                            @if($currentSection && $sectionFields->count() > 0)
                                                <!-- Render last section -->
                                                <div class="card mb-4">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">{{ $currentSection->label }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @foreach($sectionFields as $sectionField)
                                                                @include('form-builders.partials.field-preview', ['field' => $sectionField])
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Submit Button -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <button type="submit" class="btn btn-primary" disabled>
                                                            {{ $formBuilder->submit_button_text ?: 'Submit Registration' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-file-earmark-text fs-1 d-block mb-3"></i>
                                        <h6>No Form Fields Yet</h6>
                                        <p class="mb-3">Click "Edit Form" to add fields and customize your form layout</p>
                                        <a href="{{ route('events.form-builders.design', [$event, $formBuilder]) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil-square me-2"></i>Add Form Fields
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-event-layout>
