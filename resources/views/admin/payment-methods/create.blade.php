<x-event-layout :event="$event">
    <div class="py-4">
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>
                        Add Payment Method
                    </h2>
                    <p class="text-muted mb-0">Configure a new payment method for {{ $event->title }}</p>
                </div>
                <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Payment Methods
                </a>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h3 class="h6 mb-0 fw-bold">Payment Method Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-methods.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-medium">Payment Method Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
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
                                            <option value="card" {{ old('type') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                                            <option value="bank_transfer" {{ old('type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="digital_wallet" {{ old('type') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                                            <option value="mobile_money" {{ old('type') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                            <option value="crypto" {{ old('type') == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="code" class="form-label fw-medium">Payment Code *</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                               id="code" name="code" value="{{ old('code') }}" 
                                               placeholder="e.g., paystack, stripe, bank_transfer" required>
                                        <small class="text-muted">Unique identifier for this payment method</small>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-medium">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3" 
                                                  placeholder="Brief description of this payment method">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="icon" class="form-label fw-medium">Icon Class</label>
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" name="icon" value="{{ old('icon') }}" 
                                               placeholder="e.g., bi bi-credit-card">
                                        <small class="text-muted">Bootstrap Icons class (optional)</small>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="color" class="form-label fw-medium">Icon Color</label>
                                        <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                               id="color" name="color" value="{{ old('color', '#6c757d') }}">
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
                                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                            <small class="text-muted">Enable this payment method</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_default" 
                                                       name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="is_default">
                                                    Set as Default
                                                </label>
                                            </div>
                                            <small class="text-muted">Make this the default payment option</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label fw-medium">Sort Order</label>
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                                   value="{{ old('sort_order', 0) }}" min="0">
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Gateway Configuration -->
                                <div class="card border-0 bg-light mt-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">Gateway Settings</h6>
                                        
                                        <div class="mb-3">
                                            <label for="public_key" class="form-label fw-medium">Public Key</label>
                                            <input type="text" class="form-control" id="public_key" name="config[public_key]" 
                                                   value="{{ old('config.public_key') }}" 
                                                   placeholder="e.g., pk_live_1af24c675a36499d937b062d8c68ad3c23456">
                                            <small class="text-muted">Your payment gateway public key</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="secret_key" class="form-label fw-medium">Secret Key</label>
                                            <input type="password" class="form-control" id="secret_key" name="config[secret_key]" 
                                                   value="{{ old('config.secret_key') }}" 
                                                   placeholder="Your secret key">
                                            <small class="text-muted">Keep this secure and private</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="webhook_url" class="form-label fw-medium">Webhook URL</label>
                                            <input type="url" class="form-control" id="webhook_url" name="config[webhook_url]" 
                                                   value="{{ old('config.webhook_url') }}" 
                                                   placeholder="https://yourdomain.com/webhooks/paystack">
                                            <small class="text-muted">For payment notifications</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Payment Method
                            </button>
                        </div>
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
