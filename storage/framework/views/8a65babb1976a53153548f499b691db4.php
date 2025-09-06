<?php if (isset($component)) { $__componentOriginalb1882f8c14f0a5270b201bcf650aaac1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.event-layout','data' => ['event' => $event]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('event-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['event' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event)]); ?>
    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="bi bi-person-badge me-2 text-primary"></i>
                        Exhibitor Details
                    </h2>
                    <p class="text-muted mb-0">Complete information for <?php echo e($boothOwner->form_responses['name'] ?? 'Exhibitor'); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('events.reports.bookings', $event)); ?>" class="btn btn-outline-secondary">
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
                                Booth Owner Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Full Name</label>
                                        <div class="fw-semibold"><?php echo e($boothOwner->form_responses['name'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Email Address</label>
                                        <div class="fw-semibold"><?php echo e($boothOwner->form_responses['email'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Phone Number</label>
                                        <div class="fw-semibold"><?php echo e($boothOwner->form_responses['phone'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Website</label>
                                        <div class="fw-semibold">
                                            <?php if(isset($boothOwner->form_responses['company_website']) && $boothOwner->form_responses['company_website']): ?>
                                            <a href="<?php echo e($boothOwner->form_responses['company_website']); ?>" target="_blank" class="text-decoration-none">
                                                <?php echo e($boothOwner->form_responses['company_website']); ?>

                                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                                            </a>
                                            <?php else: ?>
                                            N/A
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Company Name</label>
                                        <div class="fw-semibold"><?php echo e($boothOwner->form_responses['company_name'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Country</label>
                                        <div class="fw-semibold"><?php echo e($boothOwner->form_responses['country'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Company Address</label>
                                        <div class="fw-semibold"><?php echo e($boothOwner->form_responses['company_address'] ?? 'N/A'); ?></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links Card -->
                    <?php if(isset($boothOwner->form_responses['social_media_links']) && is_array($boothOwner->form_responses['social_media_links'])): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-share me-2"></i>
                                Social Media Links
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php $__currentLoopData = $boothOwner->form_responses['social_media_links']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($link): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-<?php echo e($platform); ?> me-2 text-primary"></i>
                                        <a href="<?php echo e($link); ?>" target="_blank" class="text-decoration-none">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $platform))); ?>

                                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    

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
                                            <?php
                                            $paymentStatusClass = $paymentStatus === 'paid' ? 'success' :
                                            ($paymentStatus === 'pending' ? 'warning' : 'secondary');
                                            ?>
                                            <span class="badge bg-<?php echo e($paymentStatusClass); ?> bg-opacity-10 text-<?php echo e($paymentStatusClass); ?> border border-<?php echo e($paymentStatusClass); ?>">
                                                <i class="bi bi-<?php echo e($paymentStatus === 'paid' ? 'check-circle' : ($paymentStatus === 'pending' ? 'clock' : 'x-circle')); ?> me-1"></i>
                                                <?php echo e(ucfirst($paymentStatus)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Total Paid</label>
                                        <div class="fw-semibold text-success">$<?php echo e(number_format($totalPaid, 2)); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Pending Amount</label>
                                        <div class="fw-semibold text-warning">$<?php echo e(number_format($pendingAmount, 2)); ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Total Payments</label>
                                        <div class="fw-semibold"><?php echo e($payments->count()); ?> transactions</div>
                                    </div>
                                </div>
                            </div>

                            <?php if($payments->count() > 0): ?>
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-3 payment-history-header" style="cursor: pointer;" 
                                     data-bs-toggle="collapse" 
                                     data-bs-target="#paymentHistoryCollapse" 
                                     aria-expanded="false" 
                                     aria-controls="paymentHistoryCollapse">
                                    <h6 class="mb-0 me-2">Payment History</h6>
                                    <i class="bi bi-chevron-down" id="paymentHistoryIcon"></i>
                                </div>
                                <div class="collapse" id="paymentHistoryCollapse">
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
                                                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($payment->payment_reference ?? 'N/A'); ?></td>
                                                    <td>$<?php echo e(number_format($payment->amount, 2)); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo e($payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary')); ?> bg-opacity-10 text-<?php echo e($payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary')); ?> border border-<?php echo e($payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary')); ?>">
                                                            <?php echo e(ucfirst($payment->status)); ?>

                                                        </span>
                                                    </td>
                                                    <td><?php echo e($payment->created_at->format('M d, Y')); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-people me-2"></i>
                                    Booth Exhibitors (<?php echo e($boothOwner->boothMembers->count()); ?>/<?php echo e($boothOwner->booking->floorplanItem->max_capacity ?? 2); ?>)
                                </h5>
                                <?php
                                    $currentMemberCount = $boothOwner->boothMembers->count();
                                    $maxCapacity = $boothOwner->booking->floorplanItem->max_capacity ?? 2;
                                    $canAddMember = $currentMemberCount < $maxCapacity;
                                ?>
                                <button type="button" 
                                        class="btn btn-primary btn-sm <?php echo e($canAddMember ? '' : 'disabled'); ?>" 
                                        onclick="<?php echo e($canAddMember ? 'addNewMember()' : 'void(0)'); ?>"
                                        title="<?php echo e($canAddMember ? 'Add New Member' : 'Maximum capacity reached'); ?>">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Add Member
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($boothMembers->count() > 0): ?>
                                <div class="row g-3">
                                    <?php $__currentLoopData = $boothMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            // Use the BoothMemberHelper to get field values
                                            $memberName = \App\Helpers\BoothMemberHelper::getFieldValueByPurpose($member, 'member_name', 'Unknown Member');
                                            $memberEmail = \App\Helpers\BoothMemberHelper::getFieldValueByPurpose($member, 'member_email', 'No email');
                                        ?>
                                        
                                        <div class="col-md-6 col-lg-4" data-member-id="<?php echo e($member->id); ?>">
                                            <div class="card border-success h-100 booth-member-card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0 text-success">
                                                            <i class="bi bi-person-check me-2"></i><?php echo e($memberName); ?>

                                                        </h6>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                                    onclick="editBoothMember(<?php echo e($member->id); ?>, <?php echo e($member->id); ?>)"
                                                                    title="Edit Member">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                    onclick="deleteBoothMember(<?php echo e($member->id); ?>)"
                                                                    title="Delete Member">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-2">
                                                        <?php if($memberEmail !== 'No email'): ?>
                                                            <p class="card-text small text-muted mb-1">
                                                                <i class="bi bi-envelope me-1"></i><?php echo e($memberEmail); ?>

                                                            </p>
                                                        <?php endif; ?>
                                                         
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-success">Member</span>
                                                        <small class="text-muted">ID: <?php echo e($member->id); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 empty-state">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2 mb-0">No booth members have been added yet.</p>
                                    <small class="text-muted">Booth members will appear here once they register.</small>
                                </div>
                            <?php endif; ?>
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
                                                 <?php echo e($boothOwner->booking->floorplanItem->label ?? 'N/A'); ?>

                                             </span>
                                         </div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booth Name</label>
                                         <div class="fw-semibold"><?php echo e($boothOwner->form_responses['booth_name'] ?? 'N/A'); ?></div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Dimensions</label>
                                         <div class="fw-semibold">
                                             <?php echo e($boothOwner->booking->floorplanItem->effective_booth_width_meters ?? '3'); ?>m x 
                                             <?php echo e($boothOwner->booking->floorplanItem->effective_booth_height_meters ?? '3'); ?>m
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-6">
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booth Members Allowed</label>
                                         <div class="fw-semibold"><?php echo e($boothOwner->booking->floorplanItem->max_capacity ?? '5'); ?> members</div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booth Price</label>
                                         <div class="fw-semibold text-success">
                                             $<?php echo e(number_format($boothOwner->booking->floorplanItem->price ?? 0, 2)); ?>

                                         </div>
                                     </div>
                                     <div class="mb-3">
                                         <label class="form-label small text-muted mb-1">Booking Status</label>
                                         <div class="fw-semibold">
                                             <?php
                                                 $status = $boothOwner->booking->status ?? 'reserved';
                                                 $statusColors = [
                                                     'reserved' => ['bg' => 'warning', 'icon' => 'clock'],
                                                     'booked' => ['bg' => 'success', 'icon' => 'check-circle'],
                                                     'cancelled' => ['bg' => 'danger', 'icon' => 'x-circle'],
                                                     'completed' => ['bg' => 'info', 'icon' => 'flag-checkered']
                                                 ];
                                                 $statusConfig = $statusColors[$status] ?? $statusColors['reserved'];
                                             ?>
                                             <span class="badge bg-<?php echo e($statusConfig['bg']); ?> bg-opacity-10 text-<?php echo e($statusConfig['bg']); ?> border border-<?php echo e($statusConfig['bg']); ?>">
                                                 <i class="bi bi-<?php echo e($statusConfig['icon']); ?> me-1"></i>
                                                 <?php echo e(ucfirst($status)); ?>

                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                    <!-- Company Logo Card -->
                    <?php if(isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo']): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-building me-2"></i>
                                Company Logo
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="<?php echo e(Storage::url($boothOwner->form_responses['company_logo'])); ?>"
                                alt="Company Logo"
                                class="img-fluid rounded mb-3"
                                style="max-height: 150px; max-width: 100%;">
                            <div>
                                <a href="<?php echo e(Storage::url($boothOwner->form_responses['company_logo'])); ?>"
                                    download="company-logo-<?php echo e($boothOwner->form_responses['company_name'] ?? 'exhibitor'); ?>.png"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Download Logo
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Booth Branding Logo Card -->
                    <?php if(isset($boothOwner->form_responses['booth_branding_logo']) && $boothOwner->form_responses['booth_branding_logo']): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-palette me-2"></i>
                                Booth Branding Logo
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="<?php echo e(Storage::url($boothOwner->form_responses['booth_branding_logo'])); ?>"
                                alt="Booth Branding Logo"
                                class="img-fluid rounded mb-3"
                                style="max-height: 150px; max-width: 100%;">
                            <div>
                                <a href="<?php echo e(Storage::url($boothOwner->form_responses['booth_branding_logo'])); ?>"
                                    download="booth-branding-<?php echo e($boothOwner->form_responses['company_name'] ?? 'exhibitor'); ?>.png"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Download Branding
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions Card -->
                    <!-- <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-success" onclick="sendEmail(<?php echo e($boothOwner->id); ?>)">
                                    <i class="bi bi-envelope me-2"></i>Send Email
                                </button>
                                <button class="btn btn-outline-info" onclick="viewBooking(<?php echo e($boothOwner->booking->id); ?>)">
                                    <i class="bi bi-receipt me-2"></i>View Booking
                                </button>
                                <button class="btn btn-outline-warning" onclick="editBoothOwner(<?php echo e($boothOwner->id); ?>)">
                                    <i class="bi bi-pencil me-2"></i>Edit Details
                                </button>
                            </div>
                        </div>
                    </div> -->
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
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
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

    <?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/form-renderer.js')); ?>"></script>
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

            // Payment History collapse functionality
            const paymentHistoryCollapse = document.getElementById('paymentHistoryCollapse');
            const paymentHistoryIcon = document.getElementById('paymentHistoryIcon');
            
            if (paymentHistoryCollapse && paymentHistoryIcon) {
                paymentHistoryCollapse.addEventListener('show.bs.collapse', function() {
                    paymentHistoryIcon.classList.remove('bi-chevron-down');
                    paymentHistoryIcon.classList.add('bi-chevron-up');
                });
                
                paymentHistoryCollapse.addEventListener('hide.bs.collapse', function() {
                    paymentHistoryIcon.classList.remove('bi-chevron-up');
                    paymentHistoryIcon.classList.add('bi-chevron-down');
                });
            }
        });



                // Add new booth member
        function addNewMember() {
            // Get the booth owner ID from the current page
            const boothOwnerId = <?php echo e($boothOwner->id); ?>;
            
            // Fetch form fields for new member using the correct endpoint
            fetch(`/booth-members/new/${boothOwnerId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create a new member object with empty form responses
                        const newMember = {
                            id: 'new',
                            booth_owner_id: boothOwnerId,
                            form_responses: {}
                        };
                        
                        // Populate the form for new member (this will handle modal title and button text)
                        populateEditForm(newMember, data.formFields);
                        
                        // Add a hidden input for booth_owner_id
                        let hiddenInput = document.getElementById('booth_owner_id_input');
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.id = 'booth_owner_id_input';
                            hiddenInput.name = 'booth_owner_id';
                            hiddenInput.value = boothOwnerId;
                            document.getElementById('editBoothMemberForm').appendChild(hiddenInput);
                        } else {
                            hiddenInput.value = boothOwnerId;
                        }
                        
                        // Show the modal
                        showModal();
                    } else {
                        alert('Error loading form fields: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading form fields');
                });
        }

        // Edit booth member
        function editBoothMember(memberId, boothOwnerId) {
            // Fetch member data and form fields
            fetch(`/booth-members/${memberId}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ensure the member has the correct ID for editing
                        data.member.id = memberId;
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
            
            // Update form action and modal content based on whether this is a new member or editing existing
            if (member.id === 'new') {
                // New member - set action to create endpoint
                document.getElementById('editBoothMemberForm').action = '/booth-members';
                // Update modal title and button text
                document.querySelector('.edit-modal-header h5').innerHTML = '<i class="bi bi-person-plus me-2"></i>Add New Member';
                document.querySelector('.edit-modal-footer .btn-primary').textContent = 'Add Member';
            } else {
                // Existing member - set action to update endpoint
                document.getElementById('editBoothMemberForm').action = `/booth-members/${member.id}`;
                // Update modal title and button text
                document.querySelector('.edit-modal-header h5').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Member';
                document.querySelector('.edit-modal-footer .btn-primary').textContent = 'Save Changes';
            }
            
            // Form submission is now handled by submitEditForm() function
            console.log('Form rendered successfully');
            
            console.log('=== populateEditForm END ===');
        }

        

        // Delete booth member
        function deleteBoothMember(memberId) {
            if (confirm('Are you sure you want to delete this booth member? This action cannot be undone.')) {
                fetch(`/booth-members/${memberId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Booth member deleted successfully');
                        // Refresh the page to ensure all data is synchronized
                        location.reload();
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
            // Count the remaining member cards
            const memberCards = document.querySelectorAll('.booth-member-card');
            const memberCount = memberCards.length;
            const maxCapacity = <?php echo e($boothOwner->booking->floorplanItem->max_capacity ?? 5); ?>;
            
            // Update the header count
            const headerElement = document.querySelector('.card-header h5');
            if (headerElement) {
                headerElement.innerHTML = `<i class="bi bi-people me-2">Booth Exhibitors (${memberCount}/${maxCapacity})`;
            }
            
            // Update the Add Member button state
            const addMemberBtn = document.querySelector('.btn-primary[onclick*="addNewMember"]');
            if (addMemberBtn) {
                if (memberCount >= maxCapacity) {
                    addMemberBtn.classList.add('disabled');
                    addMemberBtn.onclick = 'void(0)';
                    addMemberBtn.title = 'Maximum capacity reached';
                } else {
                    addMemberBtn.classList.remove('disabled');
                    addMemberBtn.onclick = 'addNewMember()';
                    addMemberBtn.title = 'Add New Member';
                }
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
            
            // Manually collect form data to handle nested form_responses properly
            const formElements = form.elements;
            const processedNames = new Set(); // Track processed field names to avoid duplicates
            const formData = {}; // Use plain object instead of FormData
            
            console.log('Form action:', form.action);
            console.log('Form elements:', formElements);
            
            // Collect all form fields (avoiding duplicates)
            for (let i = 0; i < formElements.length; i++) {
                const element = formElements[i];
                if (element.name && element.type !== 'submit' && element.type !== 'button' && !processedNames.has(element.name)) {
                    processedNames.add(element.name);
                    
                    if (element.type === 'checkbox') {
                        if (element.checked) {
                            // Handle nested form_responses properly
                            if (element.name.startsWith('form_responses[')) {
                                const fieldName = element.name.match(/form_responses\[(.*?)\]/)[1];
                                if (!formData.form_responses) formData.form_responses = {};
                                if (!formData.form_responses[fieldName]) formData.form_responses[fieldName] = [];
                                formData.form_responses[fieldName].push(element.value);
                            } else {
                                formData[element.name] = element.value;
                            }
                        }
                    } else if (element.type === 'radio') {
                        if (element.checked) {
                            // Handle nested form_responses properly
                            if (element.name.startsWith('form_responses[')) {
                                const fieldName = element.name.match(/form_responses\[(.*?)\]/)[1];
                                if (!formData.form_responses) formData.form_responses = {};
                                formData.form_responses[fieldName] = element.value;
                            } else {
                                formData[element.name] = element.value;
                            }
                        }
                    } else {
                        // Handle nested form_responses properly
                        if (element.name.startsWith('form_responses[')) {
                            const fieldName = element.name.match(/form_responses\[(.*?)\]/)[1];
                            if (!formData.form_responses) formData.form_responses = {};
                            formData.form_responses[fieldName] = element.value;
                        } else {
                            formData[element.name] = element.value;
                        }
                    }
                }
            }
            
            console.log('Collected form data:', formData);
            console.log('Form responses collected:', formData.form_responses);
            
            // Determine if this is a create or update operation
            const isCreating = form.action.includes('/booth-members') && !form.action.includes('/booth-members/');
            const method = isCreating ? 'POST' : 'PUT';
            
            console.log('Operation type:', isCreating ? 'CREATE' : 'UPDATE');
            console.log('HTTP method:', method);
            
            // Get CSRF token and validate it exists
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('CSRF token not found!');
                alert('CSRF token not found. Please refresh the page and try again.');
                return;
            }
            
            console.log('CSRF token found:', csrfToken);
            
            // Add CSRF token for Laravel
            formData._token = csrfToken;
            
            // Add method override for PUT requests
            if (method === 'PUT') {
                formData._method = 'PUT';
            }
            
            console.log('Submitting form data...');
            fetch(form.action, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response received:', response);
                
                // Handle 419 CSRF token mismatch specifically
                if (response.status === 419) {
                    console.error('CSRF token mismatch (419)');
                    alert('Your session has expired. Please refresh the page and try again.');
                    hideModal();
                    return Promise.reject(new Error('CSRF token mismatch'));
                }
                
                // Handle other error statuses
                if (!response.ok) {
                    console.error('HTTP error:', response.status, response.statusText);
                    return response.json().then(data => {
                        throw new Error(`HTTP ${response.status}: ${data.message || response.statusText}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('API response data:', data);
                if (data.success) {
                    hideModal();
                    const message = isCreating ? 'Member added successfully' : 'Member updated successfully';
                    alert(message);
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Error ' + (isCreating ? 'adding' : 'updating') + ' member: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error ' + (isCreating ? 'adding' : 'updating') + ' member: ' + error.message);
            });
            
            console.log('=== submitEditForm END ===');
        }
    </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('styles'); ?>
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

        /* Payment History Collapsible Styling */
        .payment-history-header {
            transition: all 0.2s ease;
            border-radius: 6px;
            padding: 8px 12px;
        }
        
        .payment-history-header:hover {
            background-color: #f8f9fa;
        }
        
        .payment-history-header:active {
            background-color: #e9ecef;
        }
        
        .payment-history-header h6 {
            color: #495057;
            font-weight: 600;
        }
        
        .payment-history-header i {
            transition: transform 0.2s ease;
            color: #6c757d;
        }
        
        .payment-history-header:hover i {
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
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1)): ?>
<?php $attributes = $__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1; ?>
<?php unset($__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1882f8c14f0a5270b201bcf650aaac1)): ?>
<?php $component = $__componentOriginalb1882f8c14f0a5270b201bcf650aaac1; ?>
<?php unset($__componentOriginalb1882f8c14f0a5270b201bcf650aaac1); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/events/reports/booth-owner-details.blade.php ENDPATH**/ ?>