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
            <div class="col-lg-10">
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
                                    <span class="ms-2">{{ $booking->owner_details['name'] ?? 'N/A' }}</span>
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
                                                                 <a href="{{ route('bookings.owner-form-token', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="btn btn-outline-secondary">
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
    @if($booking->member_details)
        window.currentMembers = @json($booking->member_details ?? []);
    @endif
    
    // Load existing members if any
    loadExistingMembers();
    
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
    if (field && member[field.id]) {
        return member[field.id];
    }
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
        // Debug: Log the member data to see what fields we have
        console.log('Member data:', member);
        console.log('Available fields:', Object.keys(member));
        console.log('Current form data:', window.currentFormData);
        
        // Use field purposes to find the correct values
        const memberName = findFieldByPurpose(member, window.currentFormData, 'member_name') || `Member ${index + 1}`;
        const memberEmail = findFieldByPurpose(member, window.currentFormData, 'member_email') || 'No email provided';
        const memberPhone = findFieldByPurpose(member, window.currentFormData, 'member_phone');
        const memberCompany = findFieldByPurpose(member, window.currentFormData, 'member_company');
        const memberTitle = findFieldByPurpose(member, window.currentFormData, 'member_title');
        
        // Debug: Log what we found
        console.log('Found name:', memberName);
        console.log('Found email:', memberEmail);
        console.log('Found phone:', memberPhone);
        console.log('Found company:', memberCompany);
        console.log('Found title:', memberTitle);
        
        membersHtml += `
            <div class="col-md-6 col-lg-4">
                <div class="card border-success">
                    <div class="card-body p-3">
                                                 <div class="d-flex justify-content-between align-items-start mb-2">
                             <h6 class="card-title mb-0 text-success">
                                 <i class="bi bi-person-check me-2"></i>${memberName}
                             </h6>
                             <div class="btn-group btn-group-sm" role="group">
                                 <button type="button" class="btn btn-outline-primary" onclick="editMember(${index})" title="Edit Member">
                                     <i class="bi bi-pencil"></i>
                                 </button>
                                 <button type="button" class="btn btn-outline-danger" onclick="removeMember(${index})" title="Remove Member">
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
                                    <i class="bi bi-person-badge me-1"></i>${memberTitle}
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

function removeMember(index) {
    if (confirm('Are you sure you want to remove this member?')) {
        // Remove member from global array
        window.currentMembers.splice(index, 1);
        
        // Save to database
        const submitData = new FormData();
        submitData.append('member_details', JSON.stringify(window.currentMembers));
        submitData.append('_token', '{{ csrf_token() }}');
        
        // Show loading state (we'll use the member card itself for feedback)
        const memberCard = document.querySelector(`[onclick="removeMember(${index})"]`).closest('.card');
        if (memberCard) {
            memberCard.style.opacity = '0.5';
            memberCard.style.pointerEvents = 'none';
        }
        
        fetch('{{ route("bookings.save-members", ["eventSlug" => $event->slug, "accessToken" => $booking->access_token]) }}', {
            method: 'POST',
            body: submitData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display updated members
                displayExistingMembers(window.currentMembers);
                
                // Update the hidden input for form submission
                if (document.getElementById('formDataInput')) {
                    document.getElementById('formDataInput').value = JSON.stringify(window.currentMembers);
                }
                
                // Show success message
                showAlert('Member removed and saved successfully!', 'success');
            } else {
                // Restore member if save failed
                showAlert(data.message || 'Failed to remove member. Please try again.', 'danger');
                // Reload members to restore the UI
                displayExistingMembers(window.currentMembers);
            }
        })
        .catch(error => {
            console.error('Error removing member:', error);
            showAlert('An error occurred while removing member. Please try again.', 'danger');
            // Reload members to restore the UI
            displayExistingMembers(window.currentMembers);
        })
        .finally(() => {
            // Restore card state if it still exists
            if (memberCard) {
                memberCard.style.opacity = '1';
                memberCard.style.pointerEvents = 'auto';
            }
        });
    }
}

function editMember(index) {
    const member = window.currentMembers[index];
    if (!member) {
        showAlert('Member not found!', 'danger');
        return;
    }
    
    // Show the form
    const container = document.getElementById('memberFormContainer');
    container.style.display = 'block';
    
    // Update toggle button
    const toggleBtn = document.getElementById('toggleMemberForm');
    toggleBtn.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Hide Member Form';
    toggleBtn.classList.remove('btn-outline-primary');
    toggleBtn.classList.add('btn-outline-secondary');
    
    // Populate form with member data
    populateFormWithMemberData(member);
    
    // Change form buttons to edit mode
    const form = document.getElementById('memberRegistrationForm');
    const addBtn = form.querySelector('button[onclick="addMember()"]');
    const clearBtn = form.querySelector('button[onclick="clearForm()"]');
    
    // Update add button to save changes
    addBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Save Changes';
    addBtn.onclick = () => saveMemberChanges(index);
    addBtn.className = 'btn btn-warning';
    
    // Update clear button to cancel edit
    clearBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Cancel Edit';
    clearBtn.onclick = () => cancelEdit();
    clearBtn.className = 'btn btn-outline-danger';
    
    // Store editing index
    window.editingMemberIndex = index;
    
    // Scroll to form
    container.scrollIntoView({ behavior: 'smooth' });
}

function saveMemberChanges(index) {
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
    
               // Show loading state
           const saveBtn = document.querySelector('button[onclick*="saveMemberChanges"]');
           let originalText = '';
           if (saveBtn) {
               originalText = saveBtn.innerHTML;
               saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';
               saveBtn.disabled = true;
           }
    
    // Update member in global array
    window.currentMembers[index] = formData;
    
    // Save to database
    const submitData = new FormData();
    submitData.append('member_details', JSON.stringify(window.currentMembers));
    submitData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("bookings.save-members", ["eventSlug" => $event->slug, "accessToken" => $booking->access_token]) }}', {
        method: 'POST',
        body: submitData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display updated members
            displayExistingMembers(window.currentMembers);
            
            // Update the hidden input for form submission
            if (document.getElementById('formDataInput')) {
                document.getElementById('formDataInput').value = JSON.stringify(window.currentMembers);
            }
            
            // Reset form to add mode and hide form
            resetFormToAddMode();
            hideFormAfterAction();
            
            // Show success message
            showAlert('Member updated and saved successfully!', 'success');
            
            // Clear editing index
            delete window.editingMemberIndex;
        } else {
            showAlert(data.message || 'Failed to save member changes. Please try again.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving member changes:', error);
        showAlert('An error occurred while saving changes. Please try again.', 'danger');
    })
               .finally(() => {
               // Restore button state
               if (saveBtn) {
                   saveBtn.innerHTML = originalText;
                   saveBtn.disabled = false;
               }
           });
}

function cancelEdit() {
    // Reset form to add mode
    resetFormToAddMode();
    
    // Hide the form
    hideFormAfterAction();
    
    // Clear editing index
    delete window.editingMemberIndex;
    
    // Show message
    showAlert('Edit cancelled. Member not changed.', 'info');
}

function resetFormToAddMode() {
    // Clear form
    clearForm();
    
    // Reset form buttons
    const form = document.getElementById('memberRegistrationForm');
    const addBtn = form.querySelector('button[onclick="saveMemberChanges()"]') || 
                   form.querySelector('button[onclick="addMember()"]') ||
                   form.querySelector('button:last-child');
    const clearBtn = form.querySelector('button[onclick="cancelEdit()"]') || 
                     form.querySelector('button[onclick="clearForm()"]') ||
                     form.querySelector('button:first-of-type');
    
    // Restore add button
    addBtn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add Member';
    addBtn.onclick = () => addMember();
    addBtn.className = 'btn btn-success';
    
    // Restore clear button
    clearBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Clear Form';
    clearBtn.onclick = () => clearForm();
    clearBtn.className = 'btn btn-outline-secondary';
}

function hideFormAfterAction() {
    // Hide the form
    const container = document.getElementById('memberFormContainer');
    container.style.display = 'none';
    
    // Update toggle button to "Add Another Member"
    const toggleBtn = document.getElementById('toggleMemberForm');
    toggleBtn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add Another Member';
    toggleBtn.classList.remove('btn-outline-secondary');
    toggleBtn.classList.add('btn-outline-primary');
}

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

function populateFormWithMemberData(member) {
    const form = document.getElementById('memberRegistrationForm');
    
    // Clear form first
    form.reset();
    
    // Populate each field with member data
    Object.keys(member).forEach(fieldId => {
        const input = form.querySelector(`[name="${fieldId}"]`);
        if (input) {
            if (input.type === 'checkbox') {
                // Handle checkboxes - check if value is in member's array
                if (Array.isArray(member[fieldId])) {
                    member[fieldId].forEach(value => {
                        const checkbox = form.querySelector(`[name="${fieldId}"][value="${value}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
            } else if (input.type === 'radio') {
                // Handle radio buttons
                const radio = form.querySelector(`[name="${fieldId}"][value="${member[fieldId]}"]`);
                if (radio) radio.checked = true;
            } else {
                // Handle regular inputs
                input.value = member[fieldId] || '';
            }
        }
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

function addMember() {
    // Check if we're in edit mode
    if (window.editingMemberIndex !== undefined) {
        saveMemberChanges(window.editingMemberIndex);
        return;
    }
    
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
    
    // Add member to the global array
    window.currentMembers.push(formData);
    
    // Save to database
    const submitData = new FormData();
    submitData.append('member_details', JSON.stringify(window.currentMembers));
    submitData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("bookings.save-members", ["eventSlug" => $event->slug, "accessToken" => $booking->access_token]) }}', {
        method: 'POST',
        body: submitData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display updated members
            displayExistingMembers(window.currentMembers);
            
            // Update the hidden input for form submission
            if (document.getElementById('formDataInput')) {
                document.getElementById('formDataInput').value = JSON.stringify(window.currentMembers);
            }
            
            // Clear the form
            clearForm();
            
            // Hide the form automatically
            hideFormAfterAction();
            
            // Show success message
            showAlert('Member added and saved successfully!', 'success');
            
            // Log for debugging
            console.log('Member added:', formData);
            console.log('Total members:', window.currentMembers.length);
        } else {
            // Remove member from array if save failed
            window.currentMembers.pop();
            showAlert(data.message || 'Failed to save member. Please try again.', 'danger');
        }
    })
    .catch(error => {
        // Remove member from array if save failed
        window.currentMembers.pop();
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
    
    return formData;
}



function continueToPayment() {
    // Redirect to payment page
    window.location.href = '{{ route("bookings.payment", ["eventSlug" => $event->slug, "accessToken" => $booking->access_token]) }}';
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
</script>
@endpush
