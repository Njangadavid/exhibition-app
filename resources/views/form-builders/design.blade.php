<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-pencil-square me-2 text-success"></i>
                        Design Form - {{ $formBuilder->name }}
                    </h2>
                    <p class="text-muted mb-0">Customize the form layout and fields for {{ $event->title }}</p>
                </div>
                <a href="{{ route('events.form-builders.edit', [$event, $formBuilder]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Form Settings
                </a>
            </div>

            <form action="{{ route('events.form-builders.update', [$event, $formBuilder]) }}" method="POST" id="formBuilderForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Available Fields Panel -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-puzzle me-2"></i>Available Fields
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <!-- Section Button -->
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="addSection()">
                                            <i class="bi bi-layout-three-columns me-1"></i>Add Section
                                        </button>
                                    </div>
                                    <div class="col-12"><hr class="my-2"></div>
                                    
                                    @foreach($fieldTypes as $type => $label)
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 field-type-btn" 
                                                data-field-type="{{ $type }}" data-field-label="{{ $label }}">
                                            {{ $label }}
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Builder Canvas -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-pencil-square me-2"></i>Form Builder
                                </h5>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="previewForm">
                                        <i class="bi bi-eye me-1"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Save Form Design
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Form Canvas -->
                                <div id="formCanvas" class="border rounded p-3 min-vh-75" style="min-height: 400px;">
                                    <div class="text-center text-muted py-5" id="emptyState" class="{{ $formBuilder->fields->count() > 0 ? 'd-none' : '' }}">
                                        <i class="bi bi-file-earmark-text fs-1 d-block mb-3"></i>
                                        <h6>No fields added yet</h6>
                                        <p class="mb-0">Click on field types from the left panel to start building your form</p>
                                    </div>
                                    
                                    <!-- Form Fields Container -->
                                    <div id="formFields" class="{{ $formBuilder->fields->count() > 0 ? '' : 'd-none' }}">
                                        <!-- Fields will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden Fields Container for Form Submission -->
                <div id="hiddenFieldsContainer">
                    <!-- Hidden input fields will be added here for form submission -->
                </div>
            </form>
        </div>
    </div>

    <!-- Field Properties Sidebar -->
    <div id="fieldPropertiesPanel" class="position-fixed top-0 end-0 h-100 bg-dark text-light shadow-lg" style="z-index: 1050; width: 400px; transform: translateX(100%); transition: transform 0.3s ease-in-out; display: block;">
        <div class="d-flex flex-column h-100">
            <!-- Header -->
            <div class="p-4 border-bottom border-secondary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light">
                        <i class="bi bi-gear-fill me-2 text-primary"></i>Field Properties
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="closeFieldProperties()"></button>
                </div>
            </div>
            
            <!-- Scrollable Content -->
            <div class="flex-grow-1 overflow-auto">
                <form id="fieldPropertiesForm" class="p-4">
                    <div class="mb-4">
                        <label for="fieldLabel" class="form-label text-light fw-semibold">
                            <i class="bi bi-tag me-2 text-info"></i>Field Label *
                        </label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" id="fieldLabel" 
                               placeholder="Enter field label">
                    </div>
                    
                    <div class="mb-4" id="fieldPurposeContainer">
                        <label for="fieldPurpose" class="form-label text-light fw-semibold">
                            <i class="bi bi-bullseye me-2 text-warning"></i>Field Purpose
                        </label>
                        <select class="form-select bg-dark border-secondary text-light" id="fieldPurpose">
                            <option value="general">General Field</option>
                            <option value="member_name">Member Name</option>
                            <option value="member_email">Member Email</option>
                            <option value="member_phone">Member Phone</option>
                            <option value="member_company">Member Company</option>
                            <option value="member_title">Member Job Title</option>
                            <option value="member_address">Member Address</option>
                            <option value="member_id">Member ID Number</option>
                            <option value="member_bio">Member Biography</option>
                            <option value="member_notes">Member Notes</option>
                        </select>
                        <div class="form-text text-light small mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Choose what this field represents to properly display member information.
                        </div>
                    </div>
                    
                    <div class="mb-4" id="fieldPlaceholderContainer">
                        <label for="fieldPlaceholder" class="form-label text-light fw-semibold">
                            <i class="bi bi-chat-text me-2 text-info"></i>Placeholder Text
                        </label>
                        <input type="text" class="form-control bg-dark border-secondary text-light" id="fieldPlaceholder" 
                               placeholder="Enter placeholder text">
                    </div>
                    
                    <div class="mb-4" id="fieldHelpTextContainer">
                        <label for="fieldHelpText" class="form-label text-light fw-semibold">
                            <i class="bi bi-question-circle me-2 text-info"></i>Help Text
                        </label>
                        <textarea class="form-control bg-dark border-secondary text-light" id="fieldHelpText" rows="3" 
                                  placeholder="Enter help text for users"></textarea>
                    </div>
                    
                    <div class="mb-4" id="fieldWidthContainer">
                        <label for="fieldWidth" class="form-label text-light fw-semibold">
                            <i class="bi bi-arrows-expand me-2 text-info"></i>Column Width
                        </label>
                        <select class="form-select bg-dark border-secondary text-light" id="fieldWidth">
                            <option value="full">📏 Full Width (100%)</option>
                            <option value="half">📐 Half Width (50%)</option>
                            <option value="third">📊 One Third (33.33%)</option>
                            <option value="quarter">📋 One Quarter (25%)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4" id="fieldRequiredContainer">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fieldRequired">
                            <label class="form-check-label text-light" for="fieldRequired">
                                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Required Field
                            </label>
                        </div>
                    </div>

                    <div class="mb-4" id="fieldSectionContainer" style="display: none;">
                        <label for="fieldSection" class="form-label text-light fw-semibold">
                            <i class="bi bi-layout-three-columns me-2 text-info"></i>Assign to Section
                        </label>
                        <select class="form-select bg-dark border-secondary text-light" id="fieldSection">
                            <option value="">🌐 No Section (Top Level)</option>
                        </select>
                    </div>
                    
                                         <!-- Options for select, checkbox, radio -->
                     <div id="fieldOptionsContainer" class="mb-4" style="display: none;">
                         <label for="fieldOptionsTextarea" class="form-label text-light fw-semibold">
                             <i class="bi bi-list-ul me-2 text-info"></i>Options
                         </label>
                         <textarea class="form-control bg-dark border-secondary text-light" id="fieldOptionsTextarea" rows="6" 
                                   placeholder="Enter each option on a new line:&#10;Option 1&#10;Option 2&#10;Option 3"></textarea>
                         <div class="form-text text-light small mt-2">
                             <i class="bi bi-info-circle me-1"></i>
                             Enter each option on a separate line. Empty lines will be ignored.
                         </div>
                         
                         <div class="mt-3" id="defaultOptionContainer">
                             <label for="fieldDefaultOption" class="form-label text-light fw-semibold">
                                 <i class="bi bi-check-circle me-2 text-success"></i>Default Selected Option
                             </label>
                             <select class="form-select bg-dark border-secondary text-light" id="fieldDefaultOption">
                                 <option value="">No default selection</option>
                             </select>
                             <div class="form-text text-light small mt-2">
                                 <i class="bi bi-info-circle me-1"></i>
                                 Choose which option should be pre-selected (optional).
                             </div>
                         </div>
                     </div>
                </form>
            </div>
            
            <!-- Footer with Action Buttons -->
            <div class="p-4 border-top border-secondary bg-dark">
                <div class="d-grid gap-2">
                    <button type="submit" form="fieldPropertiesForm" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-light" onclick="closeFieldProperties()">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay for properties panel -->
    <div id="fieldPropertiesOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75" style="z-index: 1040; display: none; transition: opacity 0.3s ease-in-out;" onclick="closeFieldProperties()"></div>

    <style>
        /* Custom Dark Theme Styles */
        .form-control.bg-dark,
        .form-select.bg-dark {
            background-color: #2d3748 !important;
            border-color: #4a5568 !important;
            color: #e2e8f0 !important;
        }
        
        .form-control.bg-dark:focus,
        .form-select.bg-dark:focus {
            background-color: #2d3748 !important;
            border-color: #63b3ed !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 179, 237, 0.25) !important;
            color: #e2e8f0 !important;
        }
        
        .form-control.bg-dark::placeholder {
            color: #a0aec0 !important;
        }
        
        .form-check-input:checked {
            background-color: #3182ce;
            border-color: #3182ce;
        }
        
        .form-check-input:focus {
            border-color: #63b3ed;
            box-shadow: 0 0 0 0.2rem rgba(99, 179, 237, 0.25);
        }
        
        /* Smooth transitions */
        .form-control,
        .form-select,
        .form-check-input {
            transition: all 0.2s ease-in-out;
        }
        
        /* Scrollbar styling for dark theme */
        .overflow-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        .overflow-auto::-webkit-scrollbar-track {
            background: #2d3748;
        }
        
        .overflow-auto::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 4px;
        }
        
        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
        
        /* Hover effects */
        .btn-outline-info:hover {
            background-color: #3182ce;
            border-color: #3182ce;
        }
        
        /* Field options styling */
        #fieldOptionsList .form-control {
            background-color: #2d3748 !important;
            border-color: #4a5568 !important;
            color: #e2e8f0 !important;
        }
        
        #fieldOptionsList .form-control:focus {
            background-color: #2d3748 !important;
            border-color: #63b3ed !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 179, 237, 0.25) !important;
        }
        
        /* Preview field styling */
        .form-control:disabled,
        .form-select:disabled,
        .form-check-input:disabled {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .form-control:disabled::placeholder {
            color: #adb5bd !important;
        }
    </style>

    @push('scripts')
    <script>
        
        let formFields = [];
        let currentFieldIndex = -1;

        // Initialize form builder with existing fields
        document.addEventListener('DOMContentLoaded', function() {
            initializeFormBuilder();
            loadExistingFields();
        });

        function initializeFormBuilder() {
            console.log('Initializing form builder...');
            
            // Field type buttons
            const fieldButtons = document.querySelectorAll('.field-type-btn');
            console.log('Found field buttons:', fieldButtons.length);
            
            fieldButtons.forEach(btn => {
                console.log('Setting up button:', btn.textContent, btn.dataset);
                btn.addEventListener('click', function() {
                    console.log('Button clicked:', this.textContent);
                    const fieldType = this.dataset.fieldType;
                    const fieldLabel = this.dataset.fieldLabel;
                    console.log('Field type:', fieldType, 'Label:', fieldLabel);
                    addField(fieldType, fieldLabel);
                });
            });

            // Form submission
            document.getElementById('formBuilderForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (formFields.length === 0) {
                    alert('Please add at least one field to your form.');
                    return false;
                }
                
                // Validate required fields
                const validationErrors = validateFormFields();
                if (validationErrors.length > 0) {
                    const errorMessage = 'Please fix the following issues:\n\n' + validationErrors.join('\n');
                    alert(errorMessage);
                    return false;
                }
                
                console.log('Form submission started, updating hidden fields...');
                updateHiddenFields();
                console.log('Hidden fields updated, submitting form...');
                
                // Allow the form to submit naturally after hidden fields are updated
                setTimeout(() => {
                    e.target.submit();
                }, 100);
            });

            // Preview button
            document.getElementById('previewForm').addEventListener('click', function() {
                previewForm();
            });
        }

        function loadExistingFields() {
            @foreach($formBuilder->fields as $field)
            console.log('Database field data:', {
                field_id: '{{ $field->field_id }}',
                type: '{{ $field->type }}',
                label: '{{ $field->label }}',
                section_id: '{{ $field->section_id ?? '' }}'
            });
            
            const fieldObj{{ $loop->index }} = {
                id: '{{ $field->field_id }}',
                type: '{{ $field->type }}',
                label: '{{ $field->label }}',
                fieldPurpose: '{{ $field->field_purpose ?? 'general' }}',
                required: {{ $field->required ? 'true' : 'false' }},
                placeholder: '{{ $field->placeholder ?? '' }}',
                helpText: '{{ $field->help_text ?? '' }}',
                width: '{{ $field->width }}',
                options: @if($field->options) @json($field->options) @else null @endif,
                defaultOption: '{{ $field->default_option ?? '' }}',
                sectionId: '{{ $field->section_id ?? '' }}',
                sortOrder: {{ $field->sort_order }}
            };
            formFields.push(fieldObj{{ $loop->index }});
            @endforeach
            
            // First render all sections
            console.log('Loading existing fields - first rendering sections...');
            console.log('All formFields loaded:', formFields);
            formFields.forEach((field, index) => {
                if (field.type === 'section') {
                    console.log('Rendering section:', field.label, 'with ID:', field.id);
                    renderField(field, index);
                }
            });
            
            // Then render all other fields (which will now find their sections)
            console.log('Now rendering fields that belong to sections...');
            formFields.forEach((field, index) => {
                if (field.type !== 'section') {
                    console.log('Rendering field:', field.label, 'type:', field.type, 'sectionId:', field.sectionId);
                    renderField(field, index);
                }
            });
            
            updateEmptyState();
            updateHiddenFields();
        }

        function addField(type, label) {
            console.log('addField called with:', type, label);
            
            // Set smart default field purpose based on field type
            let defaultPurpose = 'general';
            if (type === 'email') {
                defaultPurpose = 'member_email';
            } else if (type === 'phone') {
                defaultPurpose = 'member_phone';
            } else if (type === 'text' && (label.toLowerCase().includes('name') || label.toLowerCase().includes('full'))) {
                defaultPurpose = 'member_name';
            } else if (type === 'text' && label.toLowerCase().includes('company')) {
                defaultPurpose = 'member_company';
            } else if (type === 'text' && label.toLowerCase().includes('title')) {
                defaultPurpose = 'member_title';
            }
            
            const field = {
                id: 'field_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9),
                type: type,
                label: label,
                fieldPurpose: defaultPurpose,
                required: false,
                placeholder: '',
                helpText: '',
                width: 'full',
                options: type === 'select' || type === 'checkbox' || type === 'radio' ? ['Option 1'] : null,
                defaultOption: null,
                sortOrder: formFields.length,
                sectionId: getActiveSection()
            };

            console.log('Created field object:', field);
            
            formFields.push(field);
            console.log('FormFields array now has:', formFields.length, 'items');
            
            renderField(field, formFields.length - 1);
            updateEmptyState();
            updateHiddenFields();
            
            // Auto-open properties panel for new fields
            setTimeout(() => {
                openFieldProperties(field, formFields.length - 1);
            }, 100);
        }

        function addSection() {
            console.log('Adding new section');
            
            const section = {
                id: 'section_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9),
                type: 'section',
                label: 'New Section',
                sortOrder: formFields.length
            };

            console.log('Created section object:', section);
            
            formFields.push(section);
            console.log('FormFields array now has:', formFields.length, 'items');
            
            renderField(section, formFields.length - 1);
            updateEmptyState();
            updateHiddenFields();
            
            // Auto-open properties panel for new section
            setTimeout(() => {
                openFieldProperties(section, formFields.length - 1);
            }, 100);
        }

        function getActiveSection() {
            // Don't auto-assign to sections - let user explicitly choose
            return null;
        }

        function renderField(field, index) {
            console.log('renderField called with:', field);
            
            const formFieldsContainer = document.getElementById('formFields');
            console.log('Form fields container:', formFieldsContainer);
            
            if (!formFieldsContainer) {
                console.error('Form fields container not found!');
                return;
            }
            
            if (field.type === 'section') {
                // Render section
                const sectionElement = document.createElement('div');
                sectionElement.className = 'section-container mb-4';
                sectionElement.dataset.fieldIndex = index;
                sectionElement.dataset.fieldId = field.id;
                
                sectionElement.innerHTML = `
                    <div class="section-header p-3 bg-primary bg-opacity-10 border rounded-top d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-layout-three-columns me-2"></i>
                            <strong>${field.label}</strong>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-field" title="Edit Section">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-field" title="Delete Section">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="section-content border border-top-0 rounded-bottom p-3 bg-light">
                        <div class="row section-fields" data-section-id="${field.id}">
                            <div class="col-12 text-center text-muted py-3">
                                <i class="bi bi-plus-circle me-2"></i>
                                Add fields to this section using the buttons on the left
                            </div>
                        </div>
                    </div>
                `;
                
                // Add event listeners
                sectionElement.querySelector('.delete-field').addEventListener('click', () => deleteField(index));
                sectionElement.querySelector('.edit-field').addEventListener('click', () => openFieldProperties(field, index));
                
                formFieldsContainer.appendChild(sectionElement);
            } else {
                // Render regular field
                const fieldElement = document.createElement('div');
                fieldElement.className = `field-item ${getColumnClass(field.width)}`;
                fieldElement.dataset.fieldIndex = index;
                fieldElement.dataset.fieldId = field.id;
                
                fieldElement.innerHTML = `
                    <div class="p-3 border rounded bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2">${field.type}</span>
                                    <strong>${field.label}</strong>
                                    ${field.required ? '<span class="text-danger ms-1">*</span>' : ''}
                                    <span class="badge bg-secondary ms-2">${field.width}</span>
                                </div>
                                                                 <div class="form-control-plaintext small text-muted">
                                     ${getFieldPreview(field)}
                                     <small class="text-muted d-block mt-1">
                                         <i class="bi bi-info-circle me-1"></i>Preview field (not functional)
                                     </small>
                                 </div>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary edit-field" title="Edit Field">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-field" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Add event listeners
                fieldElement.querySelector('.delete-field').addEventListener('click', () => deleteField(index));
                fieldElement.querySelector('.edit-field').addEventListener('click', () => openFieldProperties(field, index));

                // Find the appropriate section or main container to add the field
                if (field.sectionId) {
                    console.log('Field has sectionId:', field.sectionId);
                    console.log('Looking for section container with selector:', `[data-section-id="${field.sectionId}"]`);
                    
                    // Debug: Show all available sections in the DOM
                    const allSections = document.querySelectorAll('[data-section-id]');
                    console.log('All available section containers in DOM:', Array.from(allSections).map(s => ({
                        sectionId: s.getAttribute('data-section-id'),
                        element: s
                    })));
                    
                    const sectionContainer = document.querySelector(`[data-section-id="${field.sectionId}"]`);
                    console.log('Found section container:', sectionContainer);
                    
                    if (sectionContainer) {
                        console.log('Section container found! Adding field to section.');
                        // Remove empty state message if it exists
                        const emptyState = sectionContainer.querySelector('.text-center.text-muted');
                        if (emptyState) {
                            console.log('Removing empty state message from section');
                            emptyState.remove();
                        }
                        sectionContainer.appendChild(fieldElement);
                        console.log('Field added to section container');
                    } else {
                        console.log('Section container NOT found! Adding field to main container');
                        console.log('Field will be added to main container instead');
                        formFieldsContainer.appendChild(fieldElement);
                    }
                } else {
                    console.log('Field has no sectionId, adding to main container');
                    formFieldsContainer.appendChild(fieldElement);
                }
            }
            
            console.log('Field element appended to container');
        }

        function getColumnClass(width) {
            switch(width) {
                case 'half': return 'col-md-6';
                case 'third': return 'col-md-4';
                case 'quarter': return 'col-md-3';
                default: return 'col-12';
            }
        }

        function getFieldPreview(field) {
            switch(field.type) {
                case 'text':
                case 'email':
                case 'phone':
                case 'url':
                case 'password':
                    return `<input type="${field.type}" class="form-control" placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" disabled>`;
                case 'textarea':
                    return `<textarea class="form-control" placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" disabled></textarea>`;
                case 'select':
                    let selectOptions = `<option value="">Select ${field.label.toLowerCase()}</option>`;
                    if (field.options && field.options.length > 0) {
                        field.options.forEach(option => {
                            const selected = field.defaultOption === option ? 'selected' : '';
                            selectOptions += `<option value="${option}" ${selected}>${option}</option>`;
                        });
                    }
                    return `<select class="form-select" disabled>${selectOptions}</select>`;
                case 'checkbox':
                    let checkboxOptions = '';
                    if (field.options && field.options.length > 0) {
                        field.options.forEach(option => {
                            const checkboxChecked = field.defaultOption === option ? 'checked' : '';
                            checkboxOptions += `<div class="form-check"><input class="form-check-input" type="checkbox" value="${option}" ${checkboxChecked} disabled><label class="form-check-label">${option}</label></div>`;
                        });
                    } else {
                        checkboxOptions = `<div class="form-check"><input class="form-check-input" type="checkbox" disabled><label class="form-check-label">${field.label}</label></div>`;
                    }
                    return checkboxOptions;
                case 'radio':
                    let radioOptions = '';
                    if (field.options && field.options.length > 0) {
                        field.options.forEach(option => {
                            const radioChecked = field.defaultOption === option ? 'checked' : '';
                            radioOptions += `<div class="form-check"><input class="form-check-input" type="radio" name="${field.id}" value="${option}" ${radioChecked} disabled><label class="form-check-label">${option}</label></div>`;
                        });
                    } else {
                        radioOptions = `<div class="form-check"><input class="form-check-input" type="radio" disabled><label class="form-check-label">${field.label}</label></div>`;
                    }
                    return radioOptions;
                case 'file':
                    return `<input type="file" class="form-control" disabled>`;
                case 'date':
                    return `<input type="date" class="form-control" disabled>`;
                case 'number':
                    return `<input type="number" class="form-control" placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" disabled>`;
                default:
                    return `<input type="text" class="form-control" placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" disabled>`;
            }
        }

        function deleteField(index) {
            if (confirm('Are you sure you want to delete this field?')) {
                formFields.splice(index, 1);
                
                // Re-render all fields
                const formFieldsContainer = document.getElementById('formFields');
                formFieldsContainer.innerHTML = '';
                
                formFields.forEach((field, idx) => {
                    field.sortOrder = idx;
                    renderField(field);
                });
                
                updateEmptyState();
                updateHiddenFields();
            }
        }

        function updateEmptyState() {
            const emptyState = document.getElementById('emptyState');
            const formFieldsContainer = document.getElementById('formFields');
            
            if (formFields.length === 0) {
                emptyState.classList.remove('d-none');
                formFieldsContainer.classList.add('d-none');
            } else {
                emptyState.classList.add('d-none');
                formFieldsContainer.classList.remove('d-none');
            }
        }

        function updateHiddenFields() {
            console.log('Updating hidden fields for form submission...');
            const container = document.getElementById('hiddenFieldsContainer');
            container.innerHTML = '';
            
            formFields.forEach((field, index) => {
                console.log(`Processing field ${index}:`, {
                    id: field.id,
                    type: field.type,
                    label: field.label,
                    sectionId: field.sectionId
                });
                
                // Add original_id for mapping
                const fieldOriginalId = document.createElement('input');
                fieldOriginalId.type = 'hidden';
                fieldOriginalId.name = `fields[${index}][original_id]`;
                fieldOriginalId.value = field.id;
                container.appendChild(fieldOriginalId);
                
                // Add hidden input for field data
                const fieldData = document.createElement('input');
                fieldData.type = 'hidden';
                fieldData.name = `fields[${index}][label]`;
                fieldData.value = field.label;
                container.appendChild(fieldData);
                
                const fieldType = document.createElement('input');
                fieldType.type = 'hidden';
                fieldType.name = `fields[${index}][type]`;
                fieldType.value = field.type;
                container.appendChild(fieldType);
                
                const fieldPurpose = document.createElement('input');
                fieldPurpose.type = 'hidden';
                fieldPurpose.name = `fields[${index}][field_purpose]`;
                fieldPurpose.value = field.fieldPurpose || 'general';
                container.appendChild(fieldPurpose);
                
                if (field.type !== 'section') {
                    const fieldRequired = document.createElement('input');
                    fieldRequired.type = 'hidden';
                    fieldRequired.name = `fields[${index}][required]`;
                    fieldRequired.value = field.required ? '1' : '0';
                    container.appendChild(fieldRequired);
                    
                    const fieldPlaceholder = document.createElement('input');
                    fieldPlaceholder.type = 'hidden';
                    fieldPlaceholder.name = `fields[${index}][placeholder]`;
                    fieldPlaceholder.value = field.placeholder || '';
                    container.appendChild(fieldPlaceholder);
                    
                    const fieldHelpText = document.createElement('input');
                    fieldHelpText.type = 'hidden';
                    fieldHelpText.name = `fields[${index}][help_text]`;
                    fieldHelpText.value = field.helpText || '';
                    container.appendChild(fieldHelpText);
                    
                    const fieldWidth = document.createElement('input');
                    fieldWidth.type = 'hidden';
                    fieldWidth.name = `fields[${index}][width]`;
                    fieldWidth.value = field.width || 'full';
                    container.appendChild(fieldWidth);
                    
                    if (field.sectionId) {
                        // Find the actual section by its ID to ensure we save the correct section_id
                        const actualSection = formFields.find(f => f.id === field.sectionId);
                        console.log(`Field ${field.label} has sectionId: ${field.sectionId}, found section:`, actualSection);
                        
                        if (actualSection) {
                            const fieldSectionId = document.createElement('input');
                            fieldSectionId.type = 'hidden';
                            fieldSectionId.name = `fields[${index}][section_id]`;
                            fieldSectionId.value = actualSection.id;
                            container.appendChild(fieldSectionId);
                            console.log(`Saving field ${field.label} with section_id: ${actualSection.id}`);
                        } else {
                            console.warn(`Field ${field.label} has sectionId ${field.sectionId} but no matching section found!`);
                        }
                    }
                    
                    if (field.options) {
                        const fieldOptions = document.createElement('input');
                        fieldOptions.type = 'hidden';
                        fieldOptions.name = `fields[${index}][options]`;
                        fieldOptions.value = JSON.stringify(field.options);
                        container.appendChild(fieldOptions);
                    }
                    
                    if (field.defaultOption) {
                        const fieldDefaultOption = document.createElement('input');
                        fieldDefaultOption.type = 'hidden';
                        fieldDefaultOption.name = `fields[${index}][default_option]`;
                        fieldDefaultOption.value = field.defaultOption;
                        container.appendChild(fieldDefaultOption);
                    }
                }
            });
        }



        function previewForm() {
            if (formFields.length === 0) {
                alert('Please add at least one field to preview your form.');
                return;
            }
            
            // Create preview in new window
            const previewWindow = window.open('', '_blank');
            const previewContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Form Preview</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; background-color: #f8f9fa; }
                        .preview-form { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
                    </style>
                </head>
                <body>
                    <div class="preview-form">
                        <h3 class="mb-4 text-center">Form Preview</h3>
                        <form>
                            ${formFields.map(field => {
                                const required = field.required ? 'required' : '';
                                const placeholder = field.placeholder || `Enter ${field.label.toLowerCase()}`;
                                
                                switch(field.type) {
                                    case 'text':
                                    case 'email':
                                    case 'phone':
                                    case 'url':
                                    case 'password':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <input type="${field.type}" class="form-control" placeholder="${placeholder}" ${required}>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'textarea':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <textarea class="form-control" placeholder="${placeholder}" rows="3" ${required}></textarea>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'select':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <select class="form-select" ${required}>
                                                    <option value="">Select ${field.label.toLowerCase()}</option>
                                                    ${field.options ? field.options.map(option => `<option value="${option}">${option}</option>`).join('') : ''}
                                                </select>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'checkbox':
                                        return `
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" ${required}>
                                                    <label class="form-check-label">${field.label}${field.required ? ' *' : ''}</label>
                                                </div>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'radio':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                ${field.options ? field.options.map(option => `
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="${field.id}" value="${option}" ${required}>
                                                        <label class="form-check-label">${option}</label>
                                                    </div>
                                                `).join('') : ''}
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'file':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <input type="file" class="form-control" ${required}>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'date':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <input type="date" class="form-control" ${required}>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    case 'number':
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <input type="number" class="form-control" placeholder="${placeholder}" ${required}>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                    default:
                                        return `
                                            <div class="mb-3">
                                                <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                                <input type="text" class="form-control" placeholder="${placeholder}" ${required}>
                                                ${field.helpText ? `<div class="form-text">${field.helpText}</div>` : ''}
                                            </div>
                                        `;
                                }
                            }).join('')}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </body>
                </html>
            `;
            
            previewWindow.document.write(previewContent);
            previewWindow.document.close();
        }

        // Field Properties Panel Functions
        let currentEditingFieldIndex = -1;

        function openFieldProperties(field, index) {
            console.log('Opening properties for field:', field, 'at index:', index);
            currentEditingFieldIndex = index;
            
            // Populate form fields
            document.getElementById('fieldLabel').value = field.label;
            document.getElementById('fieldPurpose').value = field.fieldPurpose || 'general';
            document.getElementById('fieldPlaceholder').value = field.placeholder || '';
            document.getElementById('fieldHelpText').value = field.helpText || '';
            
            // Handle field type specific UI
            const isSection = field.type === 'section';
            const widthContainer = document.getElementById('fieldWidthContainer');
            const requiredContainer = document.getElementById('fieldRequiredContainer');
            const sectionContainer = document.getElementById('fieldSectionContainer');
            const optionsContainer = document.getElementById('fieldOptionsContainer');
            const placeholderContainer = document.getElementById('fieldPlaceholderContainer');
            const helpTextContainer = document.getElementById('fieldHelpTextContainer');
            
            if (isSection) {
                // Hide field-specific options for sections
                widthContainer.style.display = 'none';
                requiredContainer.style.display = 'none';
                sectionContainer.style.display = 'none';
                optionsContainer.style.display = 'none';
                placeholderContainer.style.display = 'none';
                helpTextContainer.style.display = 'none';
            } else {
                // Show field options
                widthContainer.style.display = 'block';
                requiredContainer.style.display = 'block';
                placeholderContainer.style.display = 'block';
                helpTextContainer.style.display = 'block';
                
                document.getElementById('fieldWidth').value = field.width || 'full';
                document.getElementById('fieldRequired').checked = field.required || false;
                
                // Populate section selector
                sectionContainer.style.display = 'block';
                populateSectionOptions(field.sectionId);
                
                // Show/hide options container based on field type
                if (field.type === 'select' || field.type === 'checkbox' || field.type === 'radio') {
                    optionsContainer.style.display = 'block';
                    populateFieldOptionsTextarea(field.options || []);
                    
                    // Set the default option if it exists
                    if (field.defaultOption) {
                        document.getElementById('fieldDefaultOption').value = field.defaultOption;
                    } else {
                        document.getElementById('fieldDefaultOption').value = '';
                    }
                } else {
                    optionsContainer.style.display = 'none';
                }
            }
            
            // Show panel and overlay with slide animation
            document.getElementById('fieldPropertiesOverlay').style.display = 'block';
            document.getElementById('fieldPropertiesPanel').style.transform = 'translateX(0)';
            
            // Add backdrop blur effect
            document.body.style.overflow = 'hidden';
        }

        function populateSectionOptions(selectedSectionId) {
            const sectionSelect = document.getElementById('fieldSection');
            sectionSelect.innerHTML = '<option value="">No Section (Top Level)</option>';
            
            // Add all available sections
            formFields.forEach(field => {
                if (field.type === 'section') {
                    const option = document.createElement('option');
                    option.value = field.id;
                    option.textContent = field.label;
                    if (field.id === selectedSectionId) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option);
                }
            });
        }

        function closeFieldProperties() {
            // Slide out animation
            document.getElementById('fieldPropertiesPanel').style.transform = 'translateX(100%)';
            document.getElementById('fieldPropertiesOverlay').style.display = 'none';
            
            // Restore body scroll
            document.body.style.overflow = '';
            
            // Reset after animation
            setTimeout(() => {
                currentEditingFieldIndex = -1;
            }, 300);
        }

        function populateFieldOptionsTextarea(options) {
            const optionsTextarea = document.getElementById('fieldOptionsTextarea');
            const defaultOptionSelect = document.getElementById('fieldDefaultOption');
            
            // Populate textarea
            optionsTextarea.value = options.join('\n');
            
            // Populate default option dropdown
            updateDefaultOptionsDropdown(options);
            
            // Remove any existing listeners to prevent duplicates
            optionsTextarea.removeEventListener('input', updateOptionsListener);
            
            // Add real-time listener for textarea changes
            optionsTextarea.addEventListener('input', updateOptionsListener);
        }

        // Define the listener function outside so we can remove it properly
        function updateOptionsListener() {
            const currentOptions = getFieldOptionsFromTextarea();
            updateDefaultOptionsDropdown(currentOptions);
        }

        function updateDefaultOptionsDropdown(options) {
            const defaultOptionSelect = document.getElementById('fieldDefaultOption');
            const currentValue = defaultOptionSelect.value; // Preserve current selection if still valid
            
            // Clear and rebuild dropdown
            defaultOptionSelect.innerHTML = '<option value="">No default selection</option>';
            
            // Add each option
            options.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                defaultOptionSelect.appendChild(optionElement);
            });
            
            // Restore previous selection if it still exists in the new options
            if (currentValue && options.includes(currentValue)) {
                defaultOptionSelect.value = currentValue;
            } else if (currentValue && !options.includes(currentValue)) {
                // If previous selection no longer exists, clear it
                defaultOptionSelect.value = '';
            }
        }

        function getFieldOptionsFromTextarea() {
            const optionsTextarea = document.getElementById('fieldOptionsTextarea');
            const text = optionsTextarea.value;
            
            // Split by new lines, trim whitespace, and filter out empty lines
            return text.split('\n')
                      .map(option => option.trim())
                      .filter(option => option.length > 0);
        }
        
        function validateFormFields() {
            const errors = [];
            
            formFields.forEach((field, index) => {
                // Check if field has a label
                if (!field.label || field.label.trim() === '') {
                    errors.push(`Field ${index + 1}: Label is required`);
                }
                
                // Check if required fields have proper configuration
                if (field.required && field.type !== 'section') {
                    if (field.type === 'select' || field.type === 'checkbox' || field.type === 'radio') {
                        if (!field.options || field.options.length === 0) {
                            errors.push(`Field "${field.label}": Required ${field.type} fields must have options`);
                        }
                    }
                }
                
                // Check if fields with options have valid options
                if (field.options && field.options.length > 0) {
                    const validOptions = field.options.filter(option => option && option.trim() !== '');
                    if (validOptions.length === 0) {
                        errors.push(`Field "${field.label}": Options cannot be empty`);
                    }
                }
            });
            
            return errors;
        }

        // Handle form submission for field properties
        document.getElementById('fieldPropertiesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (currentEditingFieldIndex >= 0 && currentEditingFieldIndex < formFields.length) {
                const field = formFields[currentEditingFieldIndex];
                
                // Update field properties
                field.label = document.getElementById('fieldLabel').value;
                field.fieldPurpose = document.getElementById('fieldPurpose').value;
                
                console.log('Updated field purpose to:', field.fieldPurpose);
                
                if (field.type !== 'section') {
                    field.placeholder = document.getElementById('fieldPlaceholder').value;
                    field.helpText = document.getElementById('fieldHelpText').value;
                    field.width = document.getElementById('fieldWidth').value;
                    field.required = document.getElementById('fieldRequired').checked;
                    
                    // Handle section assignment carefully
                    const selectedSectionId = document.getElementById('fieldSection').value;
                    console.log('Section assignment - selected:', selectedSectionId, 'current:', field.sectionId);
                    
                    // Only update sectionId if it's explicitly changed
                    if (selectedSectionId !== field.sectionId) {
                        field.sectionId = selectedSectionId || null;
                        console.log('Section assignment changed to:', field.sectionId);
                    }
                    
                    // Update options if applicable
                    if (field.type === 'select' || field.type === 'checkbox' || field.type === 'radio') {
                        field.options = getFieldOptionsFromTextarea();
                        field.defaultOption = document.getElementById('fieldDefaultOption').value || null;
                    }
                }
                
                // Re-render all fields
                const formFieldsContainer = document.getElementById('formFields');
                formFieldsContainer.innerHTML = '';
                
                formFields.forEach((field, idx) => {
                    field.sortOrder = idx;
                    renderField(field, idx);
                });
                
                updateEmptyState();
                updateHiddenFields();
                
                closeFieldProperties();
            }
        });
    </script>
    @endpush
</x-event-layout>
