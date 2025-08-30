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
                                <i class="bi bi-people me-2"></i>
                                Booth Exhibitors ({{ $boothOwner->boothMembers->count() }}/{{ $boothOwner->booking->floorplanItem->max_capacity ?? 5 }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($boothMembers->count() > 0)
                                <div class="row g-3">
                                    @foreach($boothMembers as $member)
                                        @php
                                            // Use the BoothMemberHelper to get field values
                                            $memberName = \App\Helpers\BoothMemberHelper::getFieldValueByPurpose($member, 'member_name', 'Unknown Member');
                                            $memberEmail = \App\Helpers\BoothMemberHelper::getFieldValueByPurpose($member, 'member_email', 'No email');
                                        @endphp
                                        
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-success h-100 booth-member-card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0 text-success">
                                                            <i class="bi bi-person-check me-2"></i>{{ $memberName }}
                                                        </h6>
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
                                                    </div>
                                                    
                                                    <div class="mb-2">
                                                        @if($memberEmail !== 'No email')
                                                            <p class="card-text small text-muted mb-1">
                                                                <i class="bi bi-envelope me-1"></i>{{ $memberEmail }}
                                                            </p>
                                                        @endif
                                                         
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-success">Member</span>
                                                        <small class="text-muted">ID: {{ $member->id }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 empty-state">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2 mb-0">No booth members have been added yet.</p>
                                    <small class="text-muted">Booth members will appear here once they register.</small>
                                </div>
                            @endif
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

    <!-- Edit Booth Member Modal - Right Side Panel -->
    <div id="editBoothMemberModal" class="edit-modal-overlay" style="display: none;">
        <div class="edit-modal-panel">
            <div class="edit-modal-header bg-primary text-white">
                <h5 class="modal-title mb-0">
                    <i class="bi bi-person-edit me-2"></i>Edit Booth Member
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="hideModal()" aria-label="Close"></button>
            </div>
            
            <div class="edit-modal-body">
                <form id="editBoothMemberForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div id="editFormFields">
                        <!-- Dynamic form fields will be loaded here -->
                    </div>
                </form>
            </div>
            
            <div class="edit-modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" onclick="hideModal()">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="submitEditForm()">
                    <i class="bi bi-check-circle me-2"></i>Save Changes
                </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/form-renderer.js') }}"></script>
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

        // Right Side Modal functions
        function showModal() {
            const modal = document.getElementById('editBoothMemberModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function hideModal() {
            const modal = document.getElementById('editBoothMemberModal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close modal when clicking on backdrop or close button
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editBoothMemberModal');
            
            // Close modal when clicking outside the panel
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });
            
            // Close modal when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
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
            console.log('=== populateEditForm START ===');
            console.log('member:', member);
            console.log('formFields:', formFields);
            
            // Use the FormRenderer utility
            const formRenderer = new FormRenderer('editFormFields');
            
            // Prepare form data structure
            const formData = {
                fields: formFields.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0)),
                sections: formFields.filter(f => f.type === 'section')
            };
            
            console.log('Prepared formData:', formData);
            console.log('Sections found:', formData.sections);
            console.log('Section fields details:', formData.sections.map(s => ({ id: s.id, section_id: s.section_id, label: s.label, type: s.type })));
            
            // Render the form
            formRenderer.renderForm(formData, member.form_responses, 'editBoothMemberForm', '');
            
            // Update form action
            document.getElementById('editBoothMemberForm').action = `/api/booth-members/${member.id}`;
            
            // Form submission is now handled by submitEditForm() function
            console.log('Form rendered successfully');
            
            console.log('=== populateEditForm END ===');
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

        // Manual form submission function
        function submitEditForm() {
            console.log('=== submitEditForm START ===');
            const form = document.getElementById('editBoothMemberForm');
            
            if (!form) {
                console.error('Form not found!');
                alert('Form not found. Please try again.');
                return;
            }
            
            console.log('Form found, collecting data...');
            const formData = new FormData(form);
            
            console.log('Form action:', form.action);
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }
            
            console.log('Submitting form data...');
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('API response data:', data);
                if (data.success) {
                    hideModal();
                    alert('Member updated successfully');
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Error updating member: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error updating member: ' + error.message);
            });
            
            console.log('=== submitEditForm END ===');
        }
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

        /* Booth Member Card Styling */
        .booth-member-card {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }
        
        .booth-member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-color: #28a745;
        }
        
        .booth-member-card .card-title {
            font-size: 0.95rem;
            line-height: 1.2;
        }
        
        .booth-member-card .card-text {
            font-size: 0.8rem;
            line-height: 1.3;
        }
        
        .booth-member-card .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .booth-member-card .badge {
            font-size: 0.7rem;
            padding: 0.35em 0.65em;
        }
        
        .booth-member-card .text-muted {
            font-size: 0.7rem;
        }
        
        /* Empty state styling */
        .empty-state {
            color: #6c757d;
        }
        
        .empty-state i {
            opacity: 0.5;
        }
        
        /* Right Side Edit Modal Styling */
        .edit-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1050;
            display: flex;
            justify-content: flex-end;
            align-items: stretch;
        }
        
        .edit-modal-panel {
            width: 500px;
            max-width: 90vw;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            animation: slideInRight 0.3s ease-out;
        }
        
        .edit-modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        
        .edit-modal-header .btn-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .edit-modal-header .btn-close:hover {
            opacity: 0.8;
        }
        
        .edit-modal-body {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            background: #f8f9fa;
        }
        
        .edit-modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            flex-shrink: 0;
            background: white;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .edit-modal-panel {
                width: 100vw;
                max-width: 100vw;
            }
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