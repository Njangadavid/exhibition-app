@extends('layouts.public')

@section('title', 'Member Registration')

@php
    $showProgress = true;
    $currentStep = 3;
@endphp

@section('content')
<div class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Selected Item Info -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-shop me-2"></i>
                            Selected Space: {{ $booking->floorplanItem->label ?? $booking->floorplanItem->item_name ?? 'Booth' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    <span class="fw-medium">Capacity:</span>
                                    <span class="ms-2">{{ $booking->floorplanItem->max_capacity ?? 'N/A' }} people</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-currency-dollar text-success me-2"></i>
                                    <span class="fw-medium">Price:</span>
                                    <span class="ms-2">${{ $booking->floorplanItem->price ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle text-info me-2"></i>
                                    <span class="fw-medium">Owner:</span>
                                    <span class="ms-2">{{ $booking->boothOwner->form_responses['name'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Member Registration Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-people-fill me-2"></i>
                            Booth Members
                        </h5>
                        <small class="text-muted">
                            @if($memberForm)
                                {{ $memberForm->description ?? 'Manage your booth members' }}
                            @else
                                Manage your booth members
                            @endif
                        </small>
                    </div>
                    <div class="card-body">
                        @if(!$memberForm)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>No member registration form found!</strong> 
                                Please contact the event organizer to set up the member registration form.
                            </div>
                        @else
                            <!-- Member Capacity Info -->
                            <div class="alert alert-info mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Booth Capacity:</strong> 
                                        <span class="badge bg-primary ms-2">{{ $booking->floorplanItem->max_capacity ?? 5 }} members</span>
                                    </div>
                                    <div>
                                        <span class="text-muted">
                                            <span id="currentMemberCount">0</span> of {{ $booking->floorplanItem->max_capacity ?? 5 }} members added
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Members Display -->
                            <div id="existingMembersContainer" class="mb-4" style="display: none;">
                                <h6 class="mb-3">
                                    <i class="bi bi-people me-2"></i>
                                    Current Members
                                </h6>
                                <div id="existingMembersList" class="row g-3">
                                    <!-- Existing members will be displayed here -->
                                </div>
                            </div>

                            <!-- Add New Member Section -->
                            <div id="addMemberSection" class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" class="btn btn-outline-primary d-flex align-items-center" id="toggleMemberForm">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Add New Member
                                    </button>
                                    <small class="text-muted">
                                        Click to show/hide the member registration form
                                    </small>
                                </div>
                                
                                <div id="memberFormContainer" style="display: none;">
                                    <!-- Dynamic form will be rendered here -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading form...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading member registration form...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                                                 <a href="{{ route('bookings.owner-form-token', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token]) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Owner Details
                                </a>
                                <div>
                                    <button type="button" class="btn btn-primary" id="continueToPaymentBtn">
                                        Continue to Payment<i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Member Edit Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editMemberModalLabel">
                    <i class="bi bi-person-edit me-2"></i>Edit Member Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editMemberForm" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" id="editMemberId" name="member_id">
                    <input type="hidden" id="editMemberIndex" name="member_index">
                    
                    <!-- Member Info Summary -->
                    <div class="alert alert-info mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-info-circle fs-4"></i>
                            </div>
                            <div class="col">
                                <h6 class="alert-heading mb-1">Editing Member</h6>
                                <p class="mb-0 small" id="editMemberSummary">Loading member information...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic form fields with sections and proper layout -->
                    <div id="editFormFields"></div>
                    
                    <!-- Enhanced Resend Email Section -->
                    <div class="card border-warning mt-4">
                        <div class="card-header bg-warning bg-opacity-10 border-warning">
                            <h6 class="mb-0 text-warning">
                                <i class="bi bi-envelope me-2"></i>Email Communication
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editResendEmail" name="resend_member_email">
                                <label class="form-check-label fw-medium" for="editResendEmail">
                                    Resend welcome email to this member
                                </label>
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Check this if you want to send the member registration email again. 
                                    This is useful if the member didn't receive the original email or if you've made significant changes.
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveMemberEdit()">
                    <i class="bi bi-check-circle me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Simple Member Delete Modal -->
<div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteMemberModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Member
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="bi bi-question-circle text-warning" style="font-size: 3rem;"></i>
                <p class="mt-3 mb-0 fw-medium">Are you sure you want to delete this member?</p>
                <p class="text-muted small">This action cannot be undone.</p>
                <input type="hidden" id="deleteMemberId" name="member_id">
                <input type="hidden" id="deleteMemberIndex" name="member_index">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteMember()">
                    <i class="bi bi-trash me-2"></i>Delete Member
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-section h6 {
        color: #495057;
        margin-bottom: 0.75rem;
    }
    
    .field-group {
        margin-bottom: 1rem;
    }
    
    .field-group label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .field-group .form-control,
    .field-group .form-select {
        border-radius: 0.375rem;
    }
    
    .field-group .form-control:focus,
    .field-group .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    
    .help-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    /* Validation styling */
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .required-field.text-danger {
        color: #dc3545 !important;
    }
</style>
@endpush

@php
    $existingMembersData = [];
    if ($transformedBoothMembers && $transformedBoothMembers->count() > 0) {
        $existingMembersData = $transformedBoothMembers->pluck('form_responses')->toArray();
    }
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($memberForm)
        // Initialize member management
        initializeMemberManagement(@json($memberForm->getFormJsonAttribute()));
    @endif
    
    // Setup toggle button
    document.getElementById('toggleMemberForm').addEventListener('click', function() {
        const container = document.getElementById('memberFormContainer');
        const button = this;
        
        if (container.style.display === 'none') {
            container.style.display = 'block';
            button.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Hide Member Form';
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-outline-secondary');
        } else {
            container.style.display = 'none';
            button.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add New Member';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-outline-primary');
        }
    });
});

function initializeMemberManagement(formData) {
    // Store formData globally for other functions to use
    window.currentFormData = formData;
    
    // Initialize global members array
    window.currentMembers = [];
    @if($transformedBoothMembers && $transformedBoothMembers->count() > 0)
        window.currentMembers = @json($transformedBoothMembers->pluck('form_responses'));
    @endif
    
    // Initialize form data and current members
    window.currentFormData = @json($memberForm);
    window.currentMembers = @json($transformedBoothMembers ?? []);
    
    // Debug: Log the booth members data structure
    console.log('=== BOOTH MEMBERS DATA STRUCTURE ===');
    console.log('Booth members:', window.currentMembers);
    if (window.currentMembers.length > 0) {
        console.log('First member structure:', window.currentMembers[0]);
        console.log('First member form_responses keys:', Object.keys(window.currentMembers[0].form_responses || {}));
        console.log('First member form_responses values:', window.currentMembers[0].form_responses);
        console.log('First member form_fields:', window.currentMembers[0].form_fields);
    }
    console.log('Current form data:', window.currentFormData);
    if (window.currentFormData && window.currentFormData.fields) {
        console.log('Form fields:', window.currentFormData.fields);
        console.log('Email field:', window.currentFormData.fields.find(f => f.field_purpose === 'member_email'));
    }
    console.log('=== END BOOTH MEMBERS DATA STRUCTURE ===');
    
    // Display existing members if any
    if (window.currentMembers.length > 0) {
        displayExistingMembers(window.currentMembers);
    }
    
    // Render the dynamic form
    renderMemberForm(formData);
    
    // Setup form submission for adding new members
    setupFormSubmission();
}

function loadExistingMembers() {
    // Check if there are existing members in the global array
    if (window.currentMembers && Array.isArray(window.currentMembers) && window.currentMembers.length > 0) {
        displayExistingMembers(window.currentMembers);
    }
}

function findFieldByPurpose(member, formData, purpose) {
    // Find the field with the specified purpose
    const field = formData.fields.find(f => f.field_purpose === purpose);
    if (field && member[field.field_id]) {
        return member[field.field_id];
    }
    return null;
}

function findMemberId(memberData) {
    // When editing a member, we should already know which member we're editing
    // The memberData should contain the member ID or we should track it in the UI
    
    console.log('=== FIND MEMBER ID DEBUG ===');
    console.log('Member data received:', memberData);
    console.log('Available keys:', Object.keys(memberData));
    console.log('Current form data:', window.currentFormData);
    
    // Check if we're currently editing a member (stored in window.currentEditingMember)
    if (window.currentEditingMember && window.currentEditingMember.id) {
        console.log('Found current editing member ID:', window.currentEditingMember.id);
        return window.currentEditingMember.id;
    }
    
    // If no current editing member, try to find by email in booth members
    let memberEmail = null;
    
    // Try to find email field dynamically
    if (window.currentFormData && window.currentFormData.fields) {
        const emailField = window.currentFormData.fields.find(f => f.field_purpose === 'member_email');
        if (emailField && memberData[emailField.field_id]) {
            memberEmail = memberData[emailField.field_id];
            console.log('Found email via field purpose:', memberEmail, 'from field:', emailField.field_id);
        }
    }
    
    // Fallback to direct email field
    if (!memberEmail) {
        memberEmail = memberData.email || memberData['field_2_1755847232'];
        console.log('Using fallback email:', memberEmail);
    }
    
    if (!memberEmail) {
        console.error('No email found in member data');
        return null;
    }
    
    console.log('Looking for member with email:', memberEmail);
    
    // Find the member in the booth members collection
    @if($transformedBoothMembers && $transformedBoothMembers->count() > 0)
        const boothMembers = @json($transformedBoothMembers);
        console.log('Booth members available:', boothMembers);
        
        const foundMember = boothMembers.find(m => {
            // Since booth members don't have field_purpose, we need to find email by field_id
            // We'll look for the email field in form_responses using the same field_id logic
            let memberEmailField = null;
            
            if (window.currentFormData && window.currentFormData.fields) {
                // Find the email field by purpose
                const emailField = window.currentFormData.fields.find(f => f.field_purpose === 'member_email');
                if (emailField && m.form_responses[emailField.field_id]) {
                    memberEmailField = m.form_responses[emailField.field_id];
                    console.log('Found email in booth member via field_id:', emailField.field_id, 'value:', memberEmailField);
                }
            }
            
            // Fallback: try common email field patterns
            if (!memberEmailField) {
                // Try to find any field that contains an email
                for (const [fieldId, value] of Object.entries(m.form_responses)) {
                    if (typeof value === 'string' && value.includes('@') && value.includes('.')) {
                        memberEmailField = value;
                        console.log('Found email in booth member via pattern matching:', fieldId, 'value:', memberEmailField);
                        break;
                    }
                }
            }
            
            // Final fallback to hardcoded field
            if (!memberEmailField) {
                memberEmailField = m.form_responses.email || m.form_responses['field_2_1755847232'];
                console.log('Using fallback email field for booth member:', memberEmailField);
            }
            
            console.log('Comparing member email:', memberEmail, 'with booth member email:', memberEmailField);
            return memberEmailField === memberEmail;
        });
        
        if (foundMember) {
            console.log('Found member in booth members:', foundMember);
            return foundMember.id;
        }
    @endif
    
    console.error('Member not found in booth members:', memberEmail);
    console.error('Available booth members:', @json($transformedBoothMembers ?? []));
    return null;
}

function displayExistingMembers(members) {
    const container = document.getElementById('existingMembersContainer');
    const list = document.getElementById('existingMembersList');
    const countSpan = document.getElementById('currentMemberCount');
    
    // Update member count
    countSpan.textContent = members.length;
    
    if (members.length === 0) {
        // Hide container if no members
        container.style.display = 'none';
        // Payment button always visible - no validation required
        return;
    }
    
    // Display existing members
    let membersHtml = '';
    members.forEach((member, index) => {
        // Comprehensive logging for debugging
        console.log('=== MEMBER DISPLAY DEBUG ===');
        console.log('Member index:', index);
        console.log('Member ID:', member.id);
        console.log('Member data:', member);
        console.log('Member form_responses:', member.form_responses);
        console.log('Member form_fields:', member.form_fields);
        console.log('Form fields keys:', Object.keys(member.form_fields || {}));
        console.log('Form fields values:', Object.values(member.form_fields || {}));
        
        // Use form fields to find the correct values by field_purpose
        const memberName = findMemberFieldValue(member, 'member_name') || `Member ${index + 1}`;
        const memberEmail = findMemberFieldValue(member, 'member_email') || 'No email provided';
        const memberPhone = findMemberFieldValue(member, 'member_phone');
        const memberCompany = findMemberFieldValue(member, 'member_company');
        const memberTitle = findMemberFieldValue(member, 'member_title');
        
        // Debug: Log what we found
        console.log('Field lookup results:');
        console.log('- member_name lookup:', findMemberFieldValue(member, 'member_name'));
        console.log('- member_email lookup:', findMemberFieldValue(member, 'member_email'));
        console.log('- member_phone lookup:', findMemberFieldValue(member, 'member_phone'));
        console.log('- member_company lookup:', findMemberFieldValue(member, 'member_company'));
        console.log('- member_title lookup:', findMemberFieldValue(member, 'member_title'));
        console.log('Final display values:');
        console.log('- Display name:', memberName);
        console.log('- Display email:', memberEmail);
        console.log('=== END MEMBER DISPLAY DEBUG ===');
        
        membersHtml += `
            <div class="col-md-6 col-lg-4">
                <div class="card border-success">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0 text-success">
                                <i class="bi bi-person-check me-2"></i>${memberName}
                            </h6>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="editMemberModal(${member.id}, ${index})" title="Edit Member">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="deleteMemberModal(${member.id}, ${index})" title="Remove Member">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-2">
                            ${memberEmail !== 'No email provided' ? `
                                <p class="card-text small text-muted mb-1">
                                    <i class="bi bi-envelope me-1"></i>${memberEmail}
                                </p>
                            ` : ''}
                            ${memberPhone ? `
                                <p class="card-text small text-muted mb-1">
                                    <i class="bi bi-telephone me-1"></i>${memberPhone}
                                </p>
                            ` : ''}
                            ${memberCompany ? `
                                <p class="card-text small text-muted mb-1">
                                    <i class="bi bi-building me-1"></i>${memberCompany}
                                </p>
                            ` : ''}
                            ${memberTitle ? `
                                <p class="card-text small text-muted mb-1">
                                    <i class="bi bi-briefcase me-1"></i>${memberTitle}
                                </p>
                            ` : ''}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">Added</span>
                            <small class="text-muted">Member ${index + 1}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    list.innerHTML = membersHtml;
    container.style.display = 'block';
    
    // Show action buttons
    document.getElementById('continueToPaymentBtn').style.display = 'inline-block';
}

// Helper function to find member field value by field_purpose
function findMemberFieldValue(member, fieldPurpose) {
    console.log(`=== FINDING FIELD VALUE FOR: ${fieldPurpose} ===`);
    
    if (!member.form_fields) {
        console.log('No form_fields available in member data');
        return null;
    }
    
    console.log('Available form_fields:', member.form_fields);
    console.log('Form fields object keys:', Object.keys(member.form_fields));
    console.log('Form fields object values:', Object.values(member.form_fields));
    
    // Find the field with the specified purpose
    const field = Object.values(member.form_fields).find(f => {
        console.log(`Checking field:`, f);
        console.log(`Field field_purpose: "${f.field_purpose}" vs looking for: "${fieldPurpose}"`);
        console.log(`Match result:`, f.field_purpose === fieldPurpose);
        return f.field_purpose === fieldPurpose;
    });
    
    if (field) {
        console.log(`Found field for ${fieldPurpose}:`, field);
        console.log(`Field ID: ${field.field_id}`);
        console.log(`Member form_responses for this field:`, member.form_responses[field.field_id]);
        
        if (member.form_responses[field.field_id]) {
            console.log(`Returning value: ${member.form_responses[field.field_id]}`);
            return member.form_responses[field.field_id];
        } else {
            console.log(`No value found in form_responses for field_id: ${field.field_id}`);
        }
    } else {
        console.log(`No field found with field_purpose: ${fieldPurpose}`);
    }
    
    console.log(`=== END FINDING FIELD VALUE FOR: ${fieldPurpose} ===`);
    return null;
}

// Simple modal-based member management functions
function editMemberModal(memberId, memberIndex) {
    const member = window.currentMembers[memberIndex];
    if (!member) {
        showAlert('Member not found!', 'danger');
        return;
    }
    
    // Set modal data
    document.getElementById('editMemberId').value = memberId;
    document.getElementById('editMemberIndex').value = memberIndex;
    
    // Populate form fields
    populateEditForm(member);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editMemberModal'));
    modal.show();
}

function populateEditForm(member) {
    const container = document.getElementById('editFormFields');
    const summaryContainer = document.getElementById('editMemberSummary');
    
    // Update member summary
    const memberName = findMemberFieldValue(member, 'member_name') || 'Unknown Member';
    const memberEmail = findMemberFieldValue(member, 'member_email') || 'No email';
    summaryContainer.innerHTML = `<strong>${memberName}</strong> (${memberEmail})`;
    
    let html = '';
    
    // Get form fields from currentFormData
    if (window.currentFormData && window.currentFormData.fields) {
        let currentSection = null;
        let rowOpen = false;
        let sectionCount = 0;
        
        window.currentFormData.fields.forEach((field, index) => {
            if (field.type === 'section') {
                // Close previous row if open
                if (rowOpen) {
                    html += '</div>';
                    rowOpen = false;
                }
                
                // Start new section
                currentSection = field.label;
                sectionCount++;
                html += `
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-collection me-2"></i>${field.label}
                            </h6>
                        </div>
                    </div>
                `;
            } else {
                // Regular field
                const value = member.form_responses[field.field_id] || '';
                const required = field.required ? 'required' : '';
                const fieldName = `edit_${field.field_id}`;
                const fieldType = field.type === 'email' ? 'email' : 'text';
                
                // Determine column width based on field width
                let colClass = 'col-12';
                if (field.width === 'half') {
                    colClass = 'col-md-6';
                } else if (field.width === 'third') {
                    colClass = 'col-md-4';
                } else if (field.width === 'quarter') {
                    colClass = 'col-md-3';
                }
                
                // Start new row if needed
                if (!rowOpen) {
                    html += '<div class="row">';
                    rowOpen = true;
                }
                
                // Add field with proper styling and validation
                html += `
                    <div class="${colClass} mb-3">
                        <label for="${fieldName}" class="form-label fw-semibold small mb-1">
                            ${field.label}
                            ${field.required ? '<span class="text-danger">*</span>' : ''}
                        </label>
                        <input type="${fieldType}" 
                               class="form-control form-control-sm" 
                               id="${fieldName}" 
                               name="${fieldName}" 
                               value="${value}" 
                               ${required}
                               placeholder="Enter ${field.label.toLowerCase()}"
                               ${field.required ? 'required' : ''}>
                        ${field.help_text ? `<div class="form-text small text-muted">${field.help_text}</div>` : ''}
                        <div class="invalid-feedback">
                            Please provide a valid ${field.label.toLowerCase()}.
                        </div>
                    </div>
                `;
                
                // Close row if it's full or if next field is a section
                const nextField = window.currentFormData.fields[index + 1];
                if (nextField && nextField.type === 'section') {
                    html += '</div>';
                    rowOpen = false;
                } else if (colClass === 'col-12' || colClass === 'col-md-6') {
                    // Close row after full-width or half-width fields
                    html += '</div>';
                    rowOpen = false;
                }
            }
        });
        
        // Close last row if open
        if (rowOpen) {
            html += '</div>';
        }
        
        // Add section count info
        if (sectionCount > 0) {
            html += `
                <div class="row mt-3">
                    <div class="col-12">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Form contains ${sectionCount} section${sectionCount > 1 ? 's' : ''} with ${window.currentFormData.fields.filter(f => f.type !== 'section').length} input fields
                        </small>
                    </div>
                </div>
            `;
        }
    }
    
    container.innerHTML = html;
}

function deleteMemberModal(memberId, memberIndex) {
    // Set modal data
    document.getElementById('deleteMemberId').value = memberId;
    document.getElementById('deleteMemberIndex').value = memberIndex;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteMemberModal'));
    modal.show();
}

function confirmDeleteMember() {
    const memberId = document.getElementById('deleteMemberId').value;
    const memberIndex = document.getElementById('deleteMemberIndex').value;
    
    console.log('Deleting member:', { memberId, memberIndex });
    
    // Get CSRF token from the edit form
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    // Send delete request with CSRF token
    fetch('{{ route("bookings.delete-member", ["eventSlug" => $event->slug, "accessToken" => $booking->boothOwner->access_token, "memberId" => ":memberId"]) }}'.replace(':memberId', memberId), {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteMemberModal'));
            modal.hide();
            
            // Reload page to show updated data
            location.reload();
        } else {
            showAlert(data.message || 'Failed to delete member', 'danger');
        }
    })
    .catch(error => {
        console.error('Error deleting member:', error);
        showAlert('An error occurred while deleting member', 'danger');
    });
}

// Add some utility functions for better UX
function showLoadingState(button, text = 'Loading...') {
    button.disabled = true;
    button.innerHTML = `<i class="bi bi-hourglass-split me-2"></i>${text}`;
}

function restoreButtonState(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
}

// Improve the save function with loading states
function saveMemberEdit() {
    const memberId = document.getElementById('editMemberId').value;
    const memberIndex = document.getElementById('editMemberIndex').value;
    const resendEmail = document.getElementById('editResendEmail').checked;
    const saveButton = document.querySelector('#editMemberModal .btn-primary');
    const originalButtonText = saveButton.innerHTML;
    
    // Show loading state
    showLoadingState(saveButton, 'Saving...');
    
    // Collect form data
    const formData = {};
    const form = document.getElementById('editMemberForm');
    form.querySelectorAll('input[name^="edit_"]').forEach(input => {
        const fieldId = input.name.replace('edit_', '');
        formData[fieldId] = input.value.trim();
    });
    
    // Add resend email flag
    if (resendEmail) {
        formData.resend_member_email = '1';
    }
    
    console.log('Saving member edit:', { memberId, memberIndex, formData });
    
    // Send update request with CSRF token
    const submitData = new FormData();
    submitData.append('member_data', JSON.stringify(formData));
    if (resendEmail) {
        submitData.append('resend_member_email', '1');
    }
    
    // Get CSRF token from the form
    const csrfToken = form.querySelector('input[name="_token"]').value;
    
    fetch('{{ route("bookings.update-member", ["eventSlug" => $event->slug, "accessToken" => $booking->boothOwner->access_token, "memberId" => ":memberId"]) }}'.replace(':memberId', memberId), {
        method: 'POST',
        body: submitData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editMemberModal'));
            modal.hide();
            
            // Show success message before reload
            showAlert('Member updated successfully!', 'success');
            
            // Reload page to show updated data
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Failed to update member', 'danger');
            restoreButtonState(saveButton, originalButtonText);
        }
    })
    .catch(error => {
        console.error('Error updating member:', error);
        showAlert('An error occurred while updating member', 'danger');
        restoreButtonState(saveButton, originalButtonText);
    });
}

function renderMemberForm(formData) {
    const container = document.getElementById('memberFormContainer');
    
    if (!formData || !formData.fields || formData.fields.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No fields have been configured for this form yet. Please contact the event organizer.
            </div>
        `;
        return;
    }
    
    let formHtml = `
        <form id="memberRegistrationForm" class="border rounded p-4 bg-light">
            <h6 class="mb-3 text-primary">
                <i class="bi bi-person-plus me-2"></i>
                New Member Details
            </h6>
    `;
    
    // Group fields by sections and get section names
    const sections = {};
    const standaloneFields = [];
    const sectionNames = {};
    
    // First pass: collect section names
    if (formData.sections) {
        formData.sections.forEach(section => {
            sectionNames[section.id] = section.name || 'Section';
        });
    }
    
    // Second pass: group fields by sections
    formData.fields.forEach(field => {
        if (field.section_id) {
            if (!sections[field.section_id]) {
                sections[field.section_id] = [];
            }
            sections[field.section_id].push(field);
        } else {
            standaloneFields.push(field);
        }
    });
    
    // Render standalone fields first
    if (standaloneFields.length > 0) {
        formHtml += '<div class="row">';
        standaloneFields.forEach(field => {
            formHtml += renderField(field);
        });
        formHtml += '</div>';
    }
    
    // Render sections
    Object.keys(sections).forEach(sectionId => {
        const sectionName = sectionNames[sectionId] || 'Section';
        formHtml += `
            <div class="form-section">
                <h6><i class="bi bi-collection me-2"></i>${sectionName}</h6>
                <div class="row">
        `;
        
        sections[sectionId].forEach(field => {
            formHtml += renderField(field);
        });
        
        formHtml += '</div></div>';
    });
    
    formHtml += `
                                         <!-- Resend Email Option (only shown when editing) -->
                             <div id="resendEmailOption" class="mb-3" style="display: none;">
                                 <div class="form-check">
                                     <input class="form-check-input" type="checkbox" id="resend_member_email" name="resend_member_email" value="1">
                                     <label class="form-check-label" for="resend_member_email">
                                         <i class="bi bi-envelope me-2"></i>
                                         Send member registration email again with updated details
                                     </label>
                                     <div class="form-text text-muted">
                                         Check this box if you'd like this member to receive a confirmation email with the updated member's information.
                                     </div>
                                 </div>
                             </div>
                             
                             <div class="d-flex justify-content-between align-items-center mt-4">
                                 <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                     <i class="bi bi-arrow-clockwise me-2"></i>Clear Form
                                 </button>
                                 <button type="button" class="btn btn-success" onclick="addMember()">
                                     <i class="bi bi-plus-circle me-2"></i>Add Member
                                 </button>
                             </div>
        </form>
    `;
    
    container.innerHTML = formHtml;
}

function setupFormSubmission() {
    // Setup continue to payment button
    document.getElementById('continueToPaymentBtn').addEventListener('click', function() {
        continueToPayment();
    });
}

// Validation function for required fields
function validateRequiredFields(form) {
    let isValid = true;
    
    // Get all required fields
    const requiredFields = form.querySelectorAll('.required-field');
    
    requiredFields.forEach(fieldLabel => {
        const fieldId = fieldLabel.getAttribute('for');
        const input = form.querySelector(`[name="${fieldId}"]`);
        
        if (input) {
            let fieldValue = '';
            
            if (input.type === 'checkbox') {
                // For checkboxes, check if any are selected
                const checkboxes = form.querySelectorAll(`[name="${fieldId}"]`);
                const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
                fieldValue = checkedBoxes.length > 0 ? 'checked' : '';
            } else if (input.type === 'radio') {
                // For radio buttons, check if any are selected
                const radios = form.querySelectorAll(`[name="${fieldId}"]`);
                const selectedRadio = Array.from(radios).find(radio => radio.checked);
                fieldValue = selectedRadio ? selectedRadio.value : '';
            } else {
                // For regular inputs
                fieldValue = input.value.trim();
            }
            
            // Remove previous error styling
            input.classList.remove('is-invalid');
            fieldLabel.classList.remove('text-danger');
            
            // Check if field is empty
            if (!fieldValue) {
                input.classList.add('is-invalid');
                fieldLabel.classList.add('text-danger');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function addMember() {
    // Clear any current editing member when adding new
    delete window.currentEditingMember;
    
    const form = document.getElementById('memberRegistrationForm');
    
    // Validate required fields
    if (!validateRequiredFields(form)) {
        showAlert('Please fill in all required fields marked with *', 'warning');
        return;
    }
    
    const formData = collectFormData(form);
    
    if (Object.keys(formData).length === 0) {
        showAlert('Please fill in the member details first.', 'warning');
        return;
    }
    
    // Check capacity
    const maxCapacity = {{ $booking->floorplanItem->max_capacity ?? 5 }};
    
    if (window.currentMembers.length >= maxCapacity) {
        showAlert(`Booth capacity reached (${maxCapacity} members). Cannot add more members.`, 'warning');
        return;
    }
    
    // Show loading state
    const addBtn = form.querySelector('button[onclick="addMember()"]');
    const originalText = addBtn.innerHTML;
    addBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Adding...';
    addBtn.disabled = true;
    
    // Save to database - only send the new member data
    const submitData = new FormData();
    submitData.append('member_details', JSON.stringify([formData])); // Send only the new member
    submitData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("bookings.save-members", ["eventSlug" => $event->slug, "accessToken" => $booking->boothOwner->access_token]) }}', {
        method: 'POST',
        body: submitData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
            .then(data => {
            if (data.success) {
                // Show success message
                showAlert('Member added successfully!', 'success');
                
                // Log for debugging
                console.log('Member added:', formData);
                console.log('Total members:', window.currentMembers.length);
                
                // Immediately update the member count and list for instant feedback
                const currentCount = window.currentMembers.length;
                const newCount = currentCount + 1;
                document.getElementById('currentMemberCount').textContent = newCount;
                
                // Add the new member to the global array temporarily
                const tempMember = {
                    id: Date.now(), // Temporary ID for display
                    form_responses: formData,
                    form_fields: window.currentFormData.fields || []
                };
                window.currentMembers.push(tempMember);
                
                // Immediately display the updated member list
                displayExistingMembers(window.currentMembers);
                
                // Clear the form
                clearForm();
                
                // Hide the form automatically
                document.getElementById('memberFormContainer').style.display = 'none';
                document.getElementById('toggleMemberForm').innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add New Member';
                document.getElementById('toggleMemberForm').classList.remove('btn-outline-secondary');
                document.getElementById('toggleMemberForm').classList.add('btn-outline-primary');
                
                // Reload the page to show the new member with proper field_purpose data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert(data.message || 'Failed to save member. Please try again.', 'danger');
            }
        })
    .catch(error => {
        console.error('Error adding member:', error);
        showAlert('An error occurred while saving member. Please try again.', 'danger');
    })
    .finally(() => {
        // Restore button state
        addBtn.innerHTML = originalText;
        addBtn.disabled = false;
    });
}

function clearForm() {
    const form = document.getElementById('memberRegistrationForm');
    form.reset();
}

function collectFormData(form) {
    const formData = {};
    
    // Collect form data from inputs
    form.querySelectorAll('[data-field-type]').forEach(input => {
        const fieldId = input.name;
        const fieldType = input.dataset.fieldType;
        
        if (fieldType === 'checkbox') {
            if (!formData[fieldId]) {
                formData[fieldId] = [];
            }
            if (input.checked) {
                formData[fieldId].push(input.value);
            }
        } else if (fieldType === 'radio') {
            if (input.checked) {
                formData[fieldId] = input.value;
            }
        } else {
            formData[fieldId] = input.value;
        }
    });
    
    // Also collect the resend email checkbox value
    const resendCheckbox = form.querySelector('#resend_member_email');
    if (resendCheckbox) {
        formData.resend_member_email = resendCheckbox.checked ? '1' : '0';
    }
    
    return formData;
}



function continueToPayment() {
    // Redirect to payment page
    window.location.href = '{{ route("bookings.payment", ["eventSlug" => $event->slug, "accessToken" => $booking->boothOwner->access_token]) }}';
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

function renderField(field) {
    const required = field.required ? 'required' : '';
    const requiredClass = field.required ? 'required-field' : '';
    const helpText = field.help_text ? `<div class="help-text">${field.help_text}</div>` : '';
    const placeholder = field.placeholder ? `placeholder="${field.placeholder}"` : '';
    
    // Handle col-size for responsive layout
    const colSize = field.col_size || 12; // Default to full width
    const colClass = `col-md-${colSize}`;
    
    let fieldHtml = `
        <div class="${colClass}">
            <div class="field-group">
                <label for="${field.id}" class="form-label ${requiredClass}">${field.label}</label>
    `;
    
    switch (field.type) {
        case 'text':
            fieldHtml += `
                <input type="text" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="text">
            `;
            break;
            
        case 'email':
            fieldHtml += `
                <input type="email" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="email">
            `;
            break;
            
        case 'phone':
            fieldHtml += `
                <input type="tel" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="phone">
            `;
            break;
            
        case 'textarea':
            fieldHtml += `
                <textarea class="form-control" id="${field.id}" name="${field.id}" rows="3" 
                          ${required} ${placeholder} data-field-type="textarea"></textarea>
            `;
            break;
            
        case 'select':
            fieldHtml += `
                <select class="form-select" id="${field.id}" name="${field.id}" ${required} data-field-type="select">
                    <option value="">Select an option</option>
            `;
            if (field.options && Array.isArray(field.options)) {
                field.options.forEach(option => {
                    const selected = field.default_option === option ? 'selected' : '';
                    fieldHtml += `<option value="${option}" ${selected}>${option}</option>`;
                });
            }
            fieldHtml += '</select>';
            break;
            
        case 'checkbox':
            if (field.options && Array.isArray(field.options)) {
                field.options.forEach(option => {
                    const checked = field.default_option === option ? 'checked' : '';
                    fieldHtml += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="${field.id}_${option}" name="${field.id}[]" 
                                   value="${option}" ${checked} data-field-type="checkbox">
                            <label class="form-check-label" for="${field.id}_${option}">${option}</label>
                        </div>
                    `;
                });
            }
            break;
            
        case 'radio':
            if (field.options && Array.isArray(field.options)) {
                field.options.forEach(option => {
                    const checked = field.default_option === option ? 'checked' : '';
                    fieldHtml += `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   id="${field.id}_${option}" name="${field.id}" 
                                   value="${option}" ${checked} data-field-type="radio">
                            <label class="form-check-label" for="${field.id}_${option}">${option}</label>
                        </div>
                    `;
                });
            }
            break;
            
        case 'file':
            fieldHtml += `
                <input type="file" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} data-field-type="file">
            `;
            break;
            
        case 'date':
            fieldHtml += `
                <input type="date" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} data-field-type="date">
            `;
            break;
            
        case 'number':
            fieldHtml += `
                <input type="number" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="number">
            `;
            break;
            
        case 'url':
            fieldHtml += `
                <input type="url" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="url">
            `;
            break;
            
        case 'password':
            fieldHtml += `
                <input type="password" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="password">
            `;
            break;
            
        case 'hidden':
            fieldHtml += `
                <input type="hidden" id="${field.id}" name="${field.id}" data-field-type="hidden">
            `;
            break;
            
        default:
            fieldHtml += `
                <input type="text" class="form-control" id="${field.id}" name="${field.id}" 
                       ${required} ${placeholder} data-field-type="text">
            `;
    }
    
    fieldHtml += helpText + '</div></div>';
    return fieldHtml;
}

function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = {};
    
    // Collect form data
    form.querySelectorAll('[data-field-type]').forEach(input => {
        const fieldId = input.name;
        const fieldType = input.dataset.fieldType;
        
        if (fieldType === 'checkbox') {
            if (!formData[fieldId]) {
                formData[fieldId] = [];
            }
            if (input.checked) {
                formData[fieldId].push(input.value);
            }
        } else if (fieldType === 'radio') {
            if (input.checked) {
                formData[fieldId] = input.value;
            }
        } else {
            formData[fieldId] = input.value;
        }
    });
    
    // Set the form data in the hidden input
    document.getElementById('formDataInput').value = JSON.stringify(formData);
    
    // Submit the form
    form.submit();
}

/**
 * Resend member registration email
 */
function resendMemberEmail() {
    if (confirm('Are you sure you want to resend the member registration email? This will send the email again to all members.')) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
        button.disabled = true;
        
        // Make AJAX request to resend member registration email
        fetch('{{ route("bookings.resend-member-email", ["eventSlug" => $event->slug, "accessToken" => $booking->boothOwner->access_token]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Member registration email has been resent successfully!');
            } else {
                alert('Failed to resend email: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to resend email. Please try again.');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>
@endpush
