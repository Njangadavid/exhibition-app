<x-event-layout :event="$event">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="bi bi-plus-circle me-2"></i>
            Create New Form - {{ $event->title }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('events.form-builders.index', $event) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Forms
            </a>
        </div>
    </div>

    <div class="card mb-4">
            <!-- Event Header -->
                <div class="position-relative">
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 150px;">
                        @if($event->logo)
                            <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="w-100 h-100 object-fit-cover">
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
                        <h1 class="h4 mb-1">{{ $event->title }}</h1>
                        <p class="mb-0 opacity-75 small">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('events.form-builders.store', $event) }}" method="POST" id="formBuilderForm">
                @csrf
                
                <!-- Simple Form Creation -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-plus-circle me-2"></i>Create New Form
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Form Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" required 
                                                   placeholder="e.g., Participant Registration">
                                        </div>

                                        <div class="mb-3">
                                            <label for="type" class="form-label">Form Type *</label>
                                            <select class="form-select" id="type" name="type" required>
                                                <option value="">Select Form Type</option>
                                                <option value="member_registration">Member Registration</option>
                                                <option value="exhibitor_registration">Exhibitor Registration</option>
                                                <option value="speaker_registration">Speaker Registration</option>
                                                <option value="delegate_registration">Delegate Registration</option>
                                                <option value="general">General Form</option>
                                            </select>
                                            <div class="form-text">Choose the purpose of this form</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                      placeholder="Describe the purpose of this form"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="draft">Draft</option>
                                                <option value="published">Published</option>
                                                <option value="archived">Archived</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="submit_button_text" class="form-label">Submit Button Text</label>
                                            <input type="text" class="form-control" id="submit_button_text" name="submit_button_text" 
                                                   value="Submit Registration" placeholder="Submit Registration">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="theme_color" class="form-label">Theme Color</label>
                                            <input type="color" class="form-control form-control-color" id="theme_color" name="theme_color" 
                                                   value="#007bff" title="Choose theme color">
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="allow_multiple_submissions" 
                                                       name="allow_multiple_submissions" value="1">
                                                <label class="form-check-label" for="allow_multiple_submissions">
                                                    Allow multiple submissions per person
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="require_login" 
                                                       name="require_login" value="1">
                                                <label class="form-check-label" for="require_login">
                                                    Require user login to submit
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="send_confirmation_email" 
                                                       name="send_confirmation_email" value="1" checked>
                                                <label class="form-check-label" for="send_confirmation_email">
                                                    Send confirmation email
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirmation_message" class="form-label">Confirmation Message</label>
                                            <textarea class="form-control" id="confirmation_message" name="confirmation_message" rows="3"
                                                      placeholder="Thank you for your registration!"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="redirect_url" class="form-label">Redirect URL (Optional)</label>
                                            <input type="url" class="form-control" id="redirect_url" name="redirect_url" 
                                                   placeholder="https://example.com/thank-you">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('events.form-builders.index', $event) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Create Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-event-layout>