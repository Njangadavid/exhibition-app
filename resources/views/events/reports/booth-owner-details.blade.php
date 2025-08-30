<x-event-layout :event="$event">
    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="bi bi-person-badge me-2 text-primary"></i>
                        Exhibitor Details
                    </h2>
                    <p class="text-muted mb-0">Complete information for {{ $boothOwner->form_responses['name'] ?? 'Exhibitor' }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('events.reports.bookings', $event) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Report
                    </a>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Print Details
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- Main Content - Left Side -->
                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Full Name</label>
                                        <div class="fw-semibold">{{ $boothOwner->form_responses['name'] ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Email Address</label>
                                        <div class="fw-semibold">{{ $boothOwner->form_responses['email'] ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Phone Number</label>
                                        <div class="fw-semibold">{{ $boothOwner->form_responses['phone'] ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Website</label>
                                        <div class="fw-semibold">
                                            @if(isset($boothOwner->form_responses['company_website']) && $boothOwner->form_responses['company_website'])
                                            <a href="{{ $boothOwner->form_responses['company_website'] }}" target="_blank" class="text-decoration-none">
                                                {{ $boothOwner->form_responses['company_website'] }}
                                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                                            </a>
                                            @else
                                            N/A
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Company Name</label>
                                        <div class="fw-semibold">{{ $boothOwner->form_responses['company_name'] ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Country</label>
                                        <div class="fw-semibold">{{ $boothOwner->form_responses['country'] ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Company Address</label>
                                        <div class="fw-semibold">{{ $boothOwner->form_responses['company_address'] ?? 'N/A' }}</div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links Card -->
                    @if(isset($boothOwner->form_responses['social_media_links']) && is_array($boothOwner->form_responses['social_media_links']))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-share me-2"></i>
                                Social Media Links
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($boothOwner->form_responses['social_media_links'] as $platform => $link)
                                @if($link)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-{{ $platform }} me-2 text-primary"></i>
                                        <a href="{{ $link }}" target="_blank" class="text-decoration-none">
                                            {{ ucfirst(str_replace('_', ' ', $platform)) }}
                                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    

                    <!-- Payment Information Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-credit-card me-2"></i>
                                Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Payment Status</label>
                                        <div class="fw-semibold">
                                            @php
                                            $paymentStatusClass = $paymentStatus === 'paid' ? 'success' :
                                            ($paymentStatus === 'pending' ? 'warning' : 'secondary');
                                            @endphp
                                            <span class="badge bg-{{ $paymentStatusClass }} bg-opacity-10 text-{{ $paymentStatusClass }} border border-{{ $paymentStatusClass }}">
                                                <i class="bi bi-{{ $paymentStatus === 'paid' ? 'check-circle' : ($paymentStatus === 'pending' ? 'clock' : 'x-circle') }} me-1"></i>
                                                {{ ucfirst($paymentStatus) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Total Paid</label>
                                        <div class="fw-semibold text-success">${{ number_format($totalPaid, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Pending Amount</label>
                                        <div class="fw-semibold text-warning">${{ number_format($pendingAmount, 2) }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Total Payments</label>
                                        <div class="fw-semibold">{{ $payments->count() }} transactions</div>
                                    </div>
                                </div>
                            </div>

                            @if($payments->count() > 0)
                            <div class="mt-3">
                                <h6 class="mb-3">Payment History</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Reference</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->payment_reference ?? 'N/A' }}</td>
                                                <td>${{ number_format($payment->amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary') }} border border-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-credit-card me-2"></i>
                                Booth Exhibitors ({{ $boothOwner->boothMembers->count() }}/{{ $boothOwner->booking->floorplanItem->max_capacity ?? 5 }})

                            </h5>
                        </div>
                        <div class="card-body">


                            <div class="mt-3">
                                 <div class="table-responsive">
                                    <table id="boothMembersTable" class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                @foreach($formFields as $field)
                                                <th>{{ $field->label }}</th>
                                                @endforeach
                                                <th class="text-center" style="width: 120px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($boothMembers as $member)
                                            <tr data-member-id="{{ $member->id }}">
                                                @foreach($formFields as $field) 
                                                <td>{{ $member->form_responses[$field->field_id] ?? 'N/A' }}</td>
                                                @endforeach
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                                onclick="editBoothMember({{ $member->id }}, {{ $member->id }})"
                                                                title="Edit Member">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="deleteBoothMember({{ $member->id }})"
                                                                title="Delete Member">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right Side Panel -->
                <div class="col-lg-4">
                                         <!-- Booth Information Card -->
                     <div class="card border-0 shadow-sm mb-4">
                         <div class="card-header bg-white py-3">
                             <h5 class="mb-0">
                                 <i class="bi bi-grid-3x3-gap me-2"></i>
                                 Booth Information
                             </h5>
                         </div>
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-6">
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booth Label</label>
                                         <div class="fw-semibold">
                                             <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                 {{ $boothOwner->booking->floorplanItem->label ?? 'N/A' }}
                                             </span>
                                         </div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booth Type</label>
                                         <div class="fw-semibold">{{ $boothOwner->booking->floorplanItem->type ?? 'N/A' }}</div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Dimensions</label>
                                         <div class="fw-semibold">
                                             {{ $boothOwner->booking->floorplanItem->effective_booth_width_meters ?? '3' }}m x 
                                             {{ $boothOwner->booking->floorplanItem->effective_booth_height_meters ?? '3' }}m
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-6">
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Max Capacity</label>
                                         <div class="fw-semibold">{{ $boothOwner->booking->floorplanItem->max_capacity ?? '5' }} members</div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booth Price</label>
                                         <div class="fw-semibold text-success">
                                             ${{ number_format($boothOwner->booking->floorplanItem->price ?? 0, 2) }}
                                         </div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booking Status</label>
                                         <div class="fw-semibold">
                                             @php
                                                 $status = $boothOwner->booking->status ?? 'reserved';
                                                 $statusColors = [
                                                     'reserved' => ['bg' => 'warning', 'icon' => 'clock'],
                                                     'booked' => ['bg' => 'success', 'icon' => 'check-circle'],
                                                     'cancelled' => ['bg' => 'danger', 'icon' => 'x-circle'],
                                                     'completed' => ['bg' => 'info', 'icon' => 'flag-checkered']
                                                 ];
                                                 $statusConfig = $statusColors[$status] ?? $statusColors['reserved'];
                                             @endphp
                                             <span class="badge bg-{{ $statusConfig['bg'] }} bg-opacity-10 text-{{ $statusConfig['bg'] }} border border-{{ $statusConfig['bg'] }}">
                                                 <i class="bi bi-{{ $statusConfig['icon'] }} me-1"></i>
                                                 {{ ucfirst($status) }}
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                    <!-- Company Logo Card -->
                    @if(isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo'])
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-building me-2"></i>
                                Company Logo
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ Storage::url($boothOwner->form_responses['company_logo']) }}"
                                alt="Company Logo"
                                class="img-fluid rounded mb-3"
                                style="max-height: 150px; max-width: 100%;">
                            <div>
                                <a href="{{ Storage::url($boothOwner->form_responses['company_logo']) }}"
                                    download="company-logo-{{ $boothOwner->form_responses['company_name'] ?? 'exhibitor' }}.png"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Download Logo
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Booth Branding Logo Card -->
                    @if(isset($boothOwner->form_responses['booth_branding_logo']) && $boothOwner->form_responses['booth_branding_logo'])
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-palette me-2"></i>
                                Booth Branding Logo
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ Storage::url($boothOwner->form_responses['booth_branding_logo']) }}"
                                alt="Booth Branding Logo"
                                class="img-fluid rounded mb-3"
                                style="max-height: 150px; max-width: 100%;">
                            <div>
                                <a href="{{ Storage::url($boothOwner->form_responses['booth_branding_logo']) }}"
                                    download="booth-branding-{{ $boothOwner->form_responses['company_name'] ?? 'exhibitor' }}.png"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Download Branding
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-success" onclick="sendEmail({{ $boothOwner->id }})">
                                    <i class="bi bi-envelope me-2"></i>Send Email
                                </button>
                                <button class="btn btn-outline-info" onclick="viewBooking({{ $boothOwner->booking->id }})">
                                    <i class="bi bi-receipt me-2"></i>View Booking
                                </button>
                                <button class="btn btn-outline-warning" onclick="editBoothOwner({{ $boothOwner->id }})">
                                    <i class="bi bi-pencil me-2"></i>Edit Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Booth Member Modal -->
    <div class="modal fade" id="editBoothMemberModal" tabindex="-1" aria-labelledby="editBoothMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBoothMemberModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Booth Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBoothMemberForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div id="editFormFields">
                            <!-- Dynamic form fields will be loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Send email to exhibitor
        function sendEmail(boothOwnerId) {
            // This would typically open an email composition interface
            alert('Email functionality would be implemented here');
        }

        // View booking details
        function viewBooking(bookingId) {
            // This would typically redirect to a booking details page
            window.open(`/bookings/${bookingId}`, '_blank');
        }

        // Edit booth owner details
        function editBoothOwner(boothOwnerId) {
            // This would typically redirect to an edit page
            alert('Edit functionality would be implemented here');
        }

        // Modal functions using vanilla JavaScript
        function showModal() {
            const modal = document.getElementById('editBoothMemberModal');
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modalBackdrop';
            document.body.appendChild(backdrop);
        }

        function hideModal() {
            const modal = document.getElementById('editBoothMemberModal');
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remove backdrop
            const backdrop = document.getElementById('modalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        // Close modal when clicking on backdrop or close button
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editBoothMemberModal');
            const closeButtons = modal.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
            
            // Close modal when clicking close buttons
            closeButtons.forEach(button => {
                button.addEventListener('click', hideModal);
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });
            
            // Close modal when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    hideModal();
                }
            });
        });

        // Edit booth member
        function editBoothMember(memberId, boothOwnerId) {
            // Fetch member data and form fields
            fetch(`/api/booth-members/${memberId}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateEditForm(data.member, data.formFields);
                        showModal();
                    } else {
                        alert('Error loading member data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading member data');
                });
        }

        // Populate edit form with member data
        function populateEditForm(member, formFields) {
            const formContainer = document.getElementById('editFormFields');
            formContainer.innerHTML = '';

            // Sort fields by sort_order
            const sortedFields = formFields.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));
            
            // Group fields by section_id
            const sections = {};
            let generalFields = [];
            
            sortedFields.forEach(field => {
                if (field.type === 'section') {
                    // Initialize section if it doesn't exist
                    if (!sections[field.section_id]) {
                        sections[field.section_id] = {
                            label: field.label,
                            fields: []
                        };
                    }
                } else if (field.section_id) {
                    // Add field to its section
                    if (!sections[field.section_id]) {
                        sections[field.section_id] = {
                            label: 'Unknown Section',
                            fields: []
                        };
                    }
                    sections[field.section_id].fields.push(field);
                } else {
                    // Field without section goes to general
                    generalFields.push(field);
                }
            });

            // Render general fields first (if any)
            if (generalFields.length > 0) {
                formContainer.innerHTML += '<div class="row">';
                generalFields.forEach(field => {
                    const fieldValue = member.form_responses[field.field_id] || '';
                    const fieldHtml = generateFieldHtml(field, fieldValue);
                    formContainer.innerHTML += fieldHtml;
                });
                formContainer.innerHTML += '</div>';
            }

            // Render each section
            Object.keys(sections).forEach(sectionId => {
                const section = sections[sectionId];
                if (section.fields.length === 0) return;

                formContainer.innerHTML += `
                    <div class="mb-4">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="bi bi-collection me-2"></i>${section.label}
                        </h6>
                        <div class="row">
                `;

                let currentRowWidth = 0;
                section.fields.forEach(field => {
                    // Use field.width or field.col_size for column sizing
                    const fieldWidth = field.width || field.col_size || 6;
                    
                    // Check if we need to start a new row
                    if (currentRowWidth + fieldWidth > 12) {
                        formContainer.innerHTML += '</div><div class="row">';
                        currentRowWidth = 0;
                    }
                    
                    const fieldValue = member.form_responses[field.field_id] || '';
                    const fieldHtml = generateFieldHtml(field, fieldValue);
                    formContainer.innerHTML += fieldHtml;
                    
                    currentRowWidth += fieldWidth;
                });

                formContainer.innerHTML += '</div></div>';
            });

            // Update form action
            document.getElementById('editBoothMemberForm').action = `/api/booth-members/${member.id}`;
        }

        // Generate HTML for form fields
        function generateFieldHtml(field, value) {
            // Use field.width, field.col_size, or default for Bootstrap column class
            let colClass;
            if (field.width || field.col_size) {
                const width = field.width || field.col_size;
                // Ensure width doesn't exceed 12 (Bootstrap's grid system)
                const safeWidth = Math.min(width, 12);
                colClass = `col-md-${safeWidth}`;
            } else {
                colClass = 'col-md-6'; // Default to half width
            }
            
            // Add required indicator and validation
            const required = field.required ? 'required' : '';
            const requiredClass = field.required ? 'required-field' : '';
            const helpText = field.help_text ? `<div class="help-text small text-muted mt-1">${field.help_text}</div>` : '';
            
            switch (field.type) {
                case 'text':
                case 'email':
                case 'tel':
                    return `
                        <div class="${colClass} mb-3">
                            <label class="form-label ${requiredClass}">
                                ${field.label}
                                ${field.required ? '<span class="text-danger">*</span>' : ''}
                            </label>
                            <input type="${field.type}" class="form-control" name="form_responses[${field.field_id}]" 
                                   value="${value}" ${required} placeholder="Enter ${field.label.toLowerCase()}">
                            ${helpText}
                        </div>
                    `;
                case 'textarea':
                    return `
                        <div class="${colClass} mb-3">
                            <label class="form-label ${requiredClass}">
                                ${field.label}
                                ${field.required ? '<span class="text-danger">*</span>' : ''}
                            </label>
                            <textarea class="form-control" name="form_responses[${field.field_id}]" 
                                      rows="3" ${required} placeholder="Enter ${field.label.toLowerCase()}">${value}</textarea>
                            ${helpText}
                        </div>
                    `;
                case 'select':
                    const options = field.options ? JSON.parse(field.options) : [];
                    let optionsHtml = '<option value="">Select...</option>';
                    options.forEach(option => {
                        const selected = option === value ? 'selected' : '';
                        optionsHtml += `<option value="${option}" ${selected}>${option}</option>`;
                    });
                    return `
                        <div class="${colClass} mb-3">
                            <label class="form-label ${requiredClass}">
                                ${field.label}
                                ${field.required ? '<span class="text-danger">*</span>' : ''}
                            </label>
                            <select class="form-select" name="form_responses[${field.field_id}]" ${required}>
                                ${optionsHtml}
                            </select>
                            ${helpText}
                        </div>
                    `;
                case 'checkbox':
                    if (field.options && Array.isArray(field.options)) {
                        let checkboxHtml = '';
                        field.options.forEach(option => {
                            const checked = value && value.includes(option) ? 'checked' : '';
                            checkboxHtml += `
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="form_responses[${field.field_id}][]" 
                                           value="${option}" ${checked} ${required}>
                                    <label class="form-check-label">${option}</label>
                                </div>
                            `;
                        });
                        return `
                            <div class="${colClass} mb-3">
                                <label class="form-label ${requiredClass}">
                                    ${field.label}
                                    ${field.required ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                ${checkboxHtml}
                                ${helpText}
                            </div>
                        `;
                    }
                    break;
                case 'radio':
                    if (field.options && Array.isArray(field.options)) {
                        let radioHtml = '';
                        field.options.forEach(option => {
                            const checked = option === value ? 'checked' : '';
                            radioHtml += `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="form_responses[${field.field_id}]" 
                                           value="${option}" ${checked} ${required}>
                                    <label class="form-check-label">${option}</label>
                                </div>
                            `;
                        });
                        return `
                            <div class="${colClass} mb-3">
                                <label class="form-label ${requiredClass}">
                                    ${field.label}
                                    ${field.required ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                ${radioHtml}
                                ${helpText}
                            </div>
                        `;
                    }
                    break;
                default:
                    return `
                        <div class="${colClass} mb-3">
                            <label class="form-label ${requiredClass}">
                                ${field.label}
                                ${field.required ? '<span class="text-danger">*</span>' : ''}
                            </label>
                            <input type="text" class="form-control" name="form_responses[${field.field_id}]" 
                                   value="${value}" ${required} placeholder="Enter ${field.label.toLowerCase()}">
                            ${helpText}
                        </div>
                    `;
            }
        }

        // Delete booth member
        function deleteBoothMember(memberId) {
            if (confirm('Are you sure you want to delete this booth member? This action cannot be undone.')) {
                fetch(`/api/booth-members/${memberId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        const row = document.querySelector(`tr[data-member-id="${memberId}"]`);
                        if (row) {
                            row.remove();
                        }
                        // Update member count
                        updateMemberCount();
                        alert('Booth member deleted successfully');
                    } else {
                        alert('Error deleting member: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting member');
                });
            }
        }

        // Update member count display
        function updateMemberCount() {
            const table = document.querySelector('#boothMembersTable tbody');
            const memberCount = table.querySelectorAll('tr').length;
            const maxCapacity = {{ $boothOwner->booking->floorplanItem->max_capacity ?? 5 }};
            
            // Update the header count
            const headerElement = document.querySelector('.card-header h5');
            if (headerElement) {
                headerElement.innerHTML = `<i class="bi bi-credit-card me-2"></i>Booth Exhibitors (${memberCount}/${maxCapacity})`;
            }
        }

        // Handle form submission
        document.getElementById('editBoothMemberForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal();
                    alert('Member updated successfully');
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Error updating member: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating member');
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .badge {
            font-size: 0.75rem;
        }

        .table th {
            font-weight: 600;
            color: #495057;
        }

        @media print {

            .btn,
            .card-header {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
    @endpush
</x-event-layout>