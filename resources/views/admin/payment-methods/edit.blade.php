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

            <!-- Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h3 class="h6 mb-0 fw-bold">Payment Method Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-medium">Payment Method Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" 
                                               placeholder="e.g., Paystack, Stripe, Bank Transfer" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="type" class="form-label fw-medium">Payment Type *</label>
                                        <select class="form-select @error('type') is-invalid @enderror" 
                                                id="type" name="type" required>
                                            <option value="">Select Payment Type</option>
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
                                    
                                    <div class="col-md-6">
                                        <label for="code" class="form-label fw-medium">Payment Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light @error('code') is-invalid @enderror" 
                                                   id="code" name="code" value="{{ old('code', $paymentMethod->code) }}" 
                                                   placeholder="Auto-generated from name" readonly>
                                            <button class="btn btn-outline-secondary" type="button" id="generateCode">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Auto-generated unique identifier (can be customized if needed)</small>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-medium">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3" 
                                                  placeholder="Brief description of this payment method">{{ old('description', $paymentMethod->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="icon" class="form-label fw-medium">Icon</label>
                                        <select class="form-select @error('icon') is-invalid @enderror" id="icon" name="icon">
                                            <option value="">Select an Icon</option>
                                            <optgroup label="Payment Icons">
                                                <option value="bi bi-credit-card" {{ old('icon', $paymentMethod->icon) == 'bi bi-credit-card' ? 'selected' : '' }}>💳 Credit Card</option>
                                                <option value="bi bi-bank" {{ old('icon', $paymentMethod->icon) == 'bi bi-bank' ? 'selected' : '' }}>🏦 Bank</option>
                                                <option value="bi bi-wallet2" {{ old('icon', $paymentMethod->icon) == 'bi bi-wallet2' ? 'selected' : '' }}>👛 Wallet</option>
                                                <option value="bi bi-phone" {{ old('icon', $paymentMethod->icon) == 'bi bi-phone' ? 'selected' : '' }}>📱 Mobile</option>
                                                <option value="bi bi-currency-exchange" {{ old('icon', $paymentMethod->icon) == 'bi bi-currency-exchange' ? 'selected' : '' }}>💱 Currency</option>
                                                <option value="bi bi-cash-coin" {{ old('icon', $paymentMethod->icon) == 'bi bi-cash-coin' ? 'selected' : '' }}>🪙 Cash</option>
                                                <option value="bi bi-paypal" {{ old('icon', $paymentMethod->icon) == 'bi bi-paypal' ? 'selected' : '' }}>PayPal</option>
                                                <option value="bi bi-stripe" {{ old('icon', $paymentMethod->icon) == 'bi bi-stripe' ? 'selected' : '' }}>Stripe</option>
                                            </optgroup>
                                            <optgroup label="General Icons">
                                                <option value="bi bi-shield-check" {{ old('icon', $paymentMethod->icon) == 'bi bi-shield-check' ? 'selected' : '' }}>🛡️ Security</option>
                                                <option value="bi bi-lightning" {{ old('icon', $paymentMethod->icon) == 'bi bi-lightning' ? 'selected' : '' }}>⚡ Fast</option>
                                                <option value="bi bi-globe" {{ old('icon', $paymentMethod->icon) == 'bi bi-globe' ? 'selected' : '' }}>🌐 Global</option>
                                                <option value="bi bi-star" {{ old('icon', $paymentMethod->icon) == 'bi bi-star' ? 'selected' : '' }}>⭐ Premium</option>
                                                <option value="bi bi-heart" {{ old('icon', $paymentMethod->icon) == 'bi bi-heart' ? 'selected' : '' }}>❤️ Favorite</option>
                                            </optgroup>
                                        </select>
                                        <small class="text-muted">Choose an icon that represents this payment method</small>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="color" class="form-label fw-medium">Icon Color</label>
                                        <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                               id="color" name="color" value="{{ old('color', $paymentMethod->color ?? '#6c757d') }}">
                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Configuration & Settings -->
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">Configuration</h6>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" 
                                                       name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                            <small class="text-muted">Enable this payment method</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_default" 
                                                       name="is_default" value="1" {{ old('is_default', $paymentMethod->is_default) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="is_default">
                                                    Set as Default
                                                </label>
                                            </div>
                                            <small class="text-muted">Make this the default payment option</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label fw-medium">Sort Order</label>
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                                   value="{{ old('sort_order', $paymentMethod->sort_order ?? 0) }}" min="0">
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Gateway Configuration -->
                                <div class="card border-0 bg-light mt-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">Gateway Settings</h6>
                                        
                                        <!-- Currency Selection -->
                                        <div class="mb-3">
                                            <label for="currency" class="form-label fw-medium">Currency *</label>
                                            <select class="form-select" id="currency" name="config[currency]" required>
                                                <option value="">Select Currency</option>
                                                <option value="USD" {{ $paymentMethod->getConfig('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                                <option value="NGN" {{ $paymentMethod->getConfig('currency') == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                                <option value="EUR" {{ $paymentMethod->getConfig('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="GBP" {{ $paymentMethod->getConfig('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                                <option value="KES" {{ $paymentMethod->getConfig('currency') == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                                <option value="GHS" {{ $paymentMethod->getConfig('currency') == 'GHS' ? 'selected' : '' }}>GHS - Ghanaian Cedi</option>
                                                <option value="ZAR" {{ $paymentMethod->getConfig('currency') == 'ZAR' ? 'selected' : '' }}>ZAR - South African Rand</option>
                                            </select>
                                            <small class="text-muted">Primary currency for transactions</small>
                                        </div>
                                        
                                        <!-- Test Mode Toggle -->
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="test_mode" 
                                                       name="config[test_mode]" value="1" {{ $paymentMethod->getConfig('test_mode') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="test_mode">
                                                    Test Mode
                                                </label>
                                            </div>
                                            <small class="text-muted">Use sandbox/test keys for development</small>
                                        </div>
                                        
                                        <!-- Dynamic Configuration Fields -->
                                        <div id="dynamicConfig">
                                            <!-- Card/Digital Wallet Fields -->
                                            <div id="cardConfig" class="config-section" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="public_key" class="form-label fw-medium">Public Key *</label>
                                                    <input type="text" class="form-control" id="public_key" name="config[public_key]" 
                                                           value="{{ $paymentMethod->getConfig('public_key') }}" 
                                                           placeholder="e.g., pk_live_1af24c675a36499d937b062d8c68ad3c23456">
                                                    <small class="text-muted">Your payment gateway public key</small>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="secret_key" class="form-label fw-medium">Secret Key *</label>
                                                    <input type="password" class="form-control" id="secret_key" name="config[secret_key]" 
                                                           value="{{ $paymentMethod->getConfig('secret_key') }}" 
                                                           placeholder="Your secret key">
                                                    <small class="text-muted">Keep this secure and private</small>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="webhook_secret" class="form-label fw-medium">Webhook Secret</label>
                                                    <input type="password" class="form-control" id="webhook_secret" name="config[webhook_secret]" 
                                                           value="{{ $paymentMethod->getConfig('webhook_secret') }}" 
                                                           placeholder="Webhook secret for notifications">
                                                    <small class="text-muted">For payment notifications</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Bank Transfer Fields -->
                                            <div id="bankTransferConfig" class="config-section" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="bank_name" class="form-label fw-medium">Bank Name *</label>
                                                    <input type="text" class="form-control" id="bank_name" name="config[bank_name]" 
                                                           value="{{ $paymentMethod->getConfig('bank_name') }}" 
                                                           placeholder="e.g., First Bank of Nigeria">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="account_name" class="form-label fw-medium">Account Name *</label>
                                                    <input type="text" class="form-control" id="account_name" name="config[account_name]" 
                                                           value="{{ $paymentMethod->getConfig('account_name') }}" 
                                                           placeholder="e.g., Your Company Name">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="account_number" class="form-label fw-medium">Account Number *</label>
                                                    <input type="text" class="form-control" id="account_number" name="config[account_number]" 
                                                           value="{{ $paymentMethod->getConfig('account_number') }}" 
                                                           placeholder="e.g., 1234567890">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="sort_code" class="form-label fw-medium">Sort Code</label>
                                                    <input type="text" class="form-control" id="sort_code" name="config[sort_code]" 
                                                           value="{{ $paymentMethod->getConfig('sort_code') }}" 
                                                           placeholder="e.g., 12-34-56">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="swift_code" class="form-label fw-medium">SWIFT Code</label>
                                                    <input type="text" class="form-control" id="swift_code" name="config[swift_code]" 
                                                           value="{{ $paymentMethod->getConfig('swift_code') }}" 
                                                           placeholder="e.g., FBNING">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="instructions" class="form-label fw-medium">Transfer Instructions</label>
                                                    <textarea class="form-control" id="instructions" name="config[instructions]" rows="3" 
                                                              placeholder="Instructions for customers making bank transfers">{{ $paymentMethod->getConfig('instructions') }}</textarea>
                                                    <small class="text-muted">Instructions for customers making bank transfers</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-outline-danger" onclick="deletePaymentMethod()">
                                <i class="bi bi-trash me-2"></i>Delete Payment Method
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
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
            
            <!-- Help Section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0">
                    <h3 class="h6 mb-0 fw-bold">
                        <i class="bi bi-question-circle me-2 text-info"></i>
                        Need Help?
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Paystack Setup</h6>
                            <ul class="small text-muted">
                                <li>Get your API keys from <a href="https://dashboard.paystack.com" target="_blank">Paystack Dashboard</a></li>
                                <li>Use test keys for development, live keys for production</li>
                                <li>Configure webhooks for payment notifications</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Security Notes</h6>
                            <ul class="small text-muted">
                                <li>Never expose secret keys in frontend code</li>
                                <li>Use environment variables for sensitive data</li>
                                <li>Test thoroughly in sandbox mode first</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-event-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const generateCodeBtn = document.getElementById('generateCode');
    const typeSelect = document.getElementById('type');
    const cardConfig = document.getElementById('cardConfig');
    const bankTransferConfig = document.getElementById('bankTransferConfig');
    const form = document.querySelector('form');

    // Function to generate payment code from name
    function generatePaymentCode(name) {
        if (!name) return '';
        
        // Convert to lowercase, replace spaces with underscores, remove special characters
        let code = name.toLowerCase()
            .replace(/[^a-z0-9\s]/g, '') // Remove special characters
            .replace(/\s+/g, '_') // Replace spaces with underscores
            .replace(/_+/g, '_') // Replace multiple underscores with single
            .trim();
        
        // Ensure it's not empty
        if (!code) {
            code = 'payment_method';
        }
        
        return code;
    }
    
    // Auto-generate code when name changes
    nameInput.addEventListener('input', function() {
        const generatedCode = generatePaymentCode(this.value);
        codeInput.value = generatedCode;
    });
    
    // Generate code button click
    generateCodeBtn.addEventListener('click', function() {
        const generatedCode = generatePaymentCode(nameInput.value);
        codeInput.value = generatedCode;
    });
    
    // Make code field editable on double-click
    codeInput.addEventListener('dblclick', function() {
        this.readOnly = false;
        this.classList.remove('bg-light');
        this.focus();
    });
    
    // Make code field readonly again when it loses focus
    codeInput.addEventListener('blur', function() {
        this.readOnly = true;
        this.classList.add('bg-light');
    });

    function showConfigSection() {
        const selectedType = typeSelect.value;
        
        // Hide all config sections first
        cardConfig.style.display = 'none';
        bankTransferConfig.style.display = 'none';
        
        // Show relevant config section
        if (selectedType === 'card' || selectedType === 'digital_wallet') {
            cardConfig.style.display = 'block';
        } else if (selectedType === 'bank_transfer') {
            bankTransferConfig.style.display = 'block';
        }
    }

    // Show initial config section
    showConfigSection();
    
    // Update config section when type changes
    typeSelect.addEventListener('change', showConfigSection);

    // Add form validation before submission
    form.addEventListener('submit', function(e) {
        const selectedType = typeSelect.value;
        const currency = document.getElementById('currency').value;
        
        // Validate currency
        if (!currency) {
            e.preventDefault();
            alert('Please select a currency for this payment method.');
            return false;
        }
        
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
