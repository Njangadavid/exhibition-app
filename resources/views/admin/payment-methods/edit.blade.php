<x-event-layout :event="$event">
    <div class="py-4">
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-pencil me-2 text-warning"></i>
                        Edit Payment Method
                    </h2>
                    <p class="text-muted mb-0">{{ $paymentMethod->name }} - {{ $event->title }}</p>
                </div>
                <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Payment Methods
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil me-2"></i>Edit Payment Method Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Basic Information</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Payment Method Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">Unique Code *</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $paymentMethod->code) }}" required>
                                    <div class="form-text">Unique identifier for this payment method (e.g., paystack, stripe)</div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Payment Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        @foreach($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type', $paymentMethod->type) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $paymentMethod->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Appearance & Settings -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Appearance & Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icon Class</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $paymentMethod->icon) }}" 
                                           placeholder="bi bi-credit-card">
                                    <div class="form-text">Bootstrap Icons class (e.g., bi bi-credit-card)</div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="color" class="form-label">Brand Color</label>
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $paymentMethod->color ?? '#0d6efd') }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (available for customers)
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" 
                                               {{ old('is_default', $paymentMethod->is_default) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Set as default payment method
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Configuration Fields -->
                        <div id="configurationSection">
                            <h6 class="fw-bold text-primary mb-3">Configuration</h6>
                            
                                                         <!-- Card-based payment configuration -->
                             <div id="cardConfig" class="config-section" style="display: none;">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="public_key" class="form-label">Public Key *</label>
                                             <input type="text" class="form-control" id="public_key" name="config[public_key]" 
                                                    value="{{ $paymentMethod->getConfig('public_key') }}">
                                             <div class="form-text">Public key from your payment gateway dashboard</div>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="secret_key" class="form-label">Secret Key *</label>
                                             <input type="password" class="form-control" id="secret_key" name="config[secret_key]" 
                                                    value="{{ $paymentMethod->getConfig('secret_key') }}">
                                             <div class="form-text">Secret key from your payment gateway dashboard</div>
                                         </div>
                                     </div>
                                 </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="webhook_secret" class="form-label">Webhook Secret</label>
                                            <input type="password" class="form-control" id="webhook_secret" name="config[webhook_secret]" 
                                                   value="{{ $paymentMethod->getConfig('webhook_secret') }}">
                                            <div class="form-text">Webhook secret for payment notifications</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="config[currency]">
                                                <option value="USD" {{ $paymentMethod->getConfig('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                                <option value="NGN" {{ $paymentMethod->getConfig('currency') == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                                <option value="EUR" {{ $paymentMethod->getConfig('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="GBP" {{ $paymentMethod->getConfig('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="test_mode" name="config[test_mode]" value="1" 
                                               {{ $paymentMethod->getConfig('test_mode') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="test_mode">
                                            Test Mode (use sandbox/test keys)
                                        </label>
                                    </div>
                                </div>
                            </div>

                                                         <!-- Bank transfer configuration -->
                             <div id="bankTransferConfig" class="config-section" style="display: none;">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="bank_name" class="form-label">Bank Name *</label>
                                             <input type="text" class="form-control" id="bank_name" name="config[bank_name]" 
                                                    value="{{ $paymentMethod->getConfig('bank_name') }}">
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="account_name" class="form-label">Account Name *</label>
                                             <input type="text" class="form-control" id="account_name" name="config[account_name]" 
                                                    value="{{ $paymentMethod->getConfig('account_name') }}">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="account_number" class="form-label">Account Number *</label>
                                             <input type="text" class="form-control" id="account_number" name="config[account_number]" 
                                                    value="{{ $paymentMethod->getConfig('account_number') }}">
                                         </div>
                                     </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sort_code" class="form-label">Sort Code</label>
                                            <input type="text" class="form-control" id="sort_code" name="config[sort_code]" 
                                                   value="{{ $paymentMethod->getConfig('sort_code') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="swift_code" class="form-label">SWIFT Code</label>
                                            <input type="text" class="form-control" id="swift_code" name="config[swift_code]" 
                                                   value="{{ $paymentMethod->getConfig('swift_code') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="config[currency]">
                                                <option value="USD" {{ $paymentMethod->getConfig('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                                <option value="NGN" {{ $paymentMethod->getConfig('currency') == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                                <option value="EUR" {{ $paymentMethod->getConfig('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="GBP" {{ $paymentMethod->getConfig('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="instructions" class="form-label">Transfer Instructions</label>
                                    <textarea class="form-control" id="instructions" name="config[instructions]" rows="3">{{ $paymentMethod->getConfig('instructions') }}</textarea>
                                    <div class="form-text">Instructions for customers making bank transfers</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-danger" onclick="deletePaymentMethod()">
                                <i class="bi bi-trash me-2"></i>Delete Payment Method
                            </button>
                            <div>
                                <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Update Payment Method
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form -->
                    <form id="deleteForm" action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-event-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const configSection = document.getElementById('configurationSection');
    const cardConfig = document.getElementById('cardConfig');
    const bankTransferConfig = document.getElementById('bankTransferConfig');
    const form = document.querySelector('form');

    function showConfigSection() {
        const selectedType = typeSelect.value;
        
        // Remove required attributes from all config fields first
        removeRequiredFromConfigFields();
        
        // Hide all config sections
        cardConfig.style.display = 'none';
        bankTransferConfig.style.display = 'none';
        
        // Show relevant config section and add required attributes
        if (selectedType === 'card' || selectedType === 'digital_wallet') {
            cardConfig.style.display = 'block';
            addRequiredToCardFields();
        } else if (selectedType === 'bank_transfer') {
            bankTransferConfig.style.display = 'block';
            addRequiredToBankFields();
        }
    }

    function removeRequiredFromConfigFields() {
        // Remove required from card fields
        const cardFields = cardConfig.querySelectorAll('input[type="text"], input[type="password"]');
        cardFields.forEach(field => field.removeAttribute('required'));
        
        // Remove required from bank fields
        const bankFields = bankTransferConfig.querySelectorAll('input[type="text"]');
        bankFields.forEach(field => field.removeAttribute('required'));
    }

    function addRequiredToCardFields() {
        const publicKey = document.getElementById('public_key');
        const secretKey = document.getElementById('secret_key');
        if (publicKey) publicKey.setAttribute('required', 'required');
        if (secretKey) secretKey.setAttribute('required', 'required');
    }

    function addRequiredToBankFields() {
        const bankName = document.getElementById('bank_name');
        const accountName = document.getElementById('account_name');
        const accountNumber = document.getElementById('account_number');
        
        if (bankName) bankName.setAttribute('required', 'required');
        if (accountName) accountName.setAttribute('required', 'required');
        if (accountNumber) accountNumber.setAttribute('required', 'required');
    }

    // Show initial config section
    showConfigSection();
    
    // Update config section when type changes
    typeSelect.addEventListener('change', showConfigSection);

    // Add form validation before submission
    form.addEventListener('submit', function(e) {
        const selectedType = typeSelect.value;
        
        // Validate required fields based on payment type
        if (selectedType === 'card' || selectedType === 'digital_wallet') {
            const publicKey = document.getElementById('public_key').value.trim();
            const secretKey = document.getElementById('secret_key').value.trim();
            
            if (!publicKey || !secretKey) {
                e.preventDefault();
                alert('Please fill in all required fields for card-based payment method.');
                return false;
            }
        } else if (selectedType === 'bank_transfer') {
            const bankName = document.getElementById('bank_name').value.trim();
            const accountName = document.getElementById('account_name').value.trim();
            const accountNumber = document.getElementById('account_number').value.trim();
            
            if (!bankName || !accountName || !accountNumber) {
                e.preventDefault();
                alert('Please fill in all required fields for bank transfer payment method.');
                return false;
            }
        }
    });
});

function deletePaymentMethod() {
    if (confirm('Are you sure you want to delete this payment method? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
