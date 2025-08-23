<x-event-layout :event="$event">
    <div class="py-4">
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-eye me-2 text-info"></i>
                        Payment Method Details
                    </h2>
                    <p class="text-muted mb-0">{{ $paymentMethod->name }} - {{ $event->title }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Payment Methods
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="h6 mb-0 fw-bold">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Basic Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-muted">Payment Method Name</label>
                                    <div class="form-control-plaintext fw-bold">{{ $paymentMethod->name }}</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-muted">Payment Code</label>
                                    <div class="form-control-plaintext">
                                        <code class="bg-light px-2 py-1 rounded">{{ $paymentMethod->code }}</code>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-muted">Payment Type</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $paymentMethod->type)) }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-muted">Status</label>
                                    <div class="form-control-plaintext">
                                        @if($paymentMethod->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($paymentMethod->description)
                                    <div class="col-12">
                                        <label class="form-label fw-medium text-muted">Description</label>
                                        <div class="form-control-plaintext">{{ $paymentMethod->description }}</div>
                                    </div>
                                @endif
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-muted">Icon</label>
                                    <div class="form-control-plaintext">
                                        @if($paymentMethod->icon)
                                            <i class="{{ $paymentMethod->icon }} fs-4" style="color: {{ $paymentMethod->color ?? '#6c757d' }};"></i>
                                            <span class="ms-2 text-muted">{{ $paymentMethod->icon }}</span>
                                        @else
                                            <span class="text-muted">No icon set</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-muted">Color</label>
                                    <div class="form-control-plaintext">
                                        @if($paymentMethod->color)
                                            <div class="d-flex align-items-center">
                                                <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $paymentMethod->color }}; border-radius: 4px; border: 1px solid #dee2e6;"></div>
                                                <code>{{ $paymentMethod->color }}</code>
                                            </div>
                                        @else
                                            <span class="text-muted">Default color</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Configuration & Settings -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="h6 mb-0 fw-bold">
                                <i class="bi bi-gear me-2 text-success"></i>
                                Configuration
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium text-muted">Default Status</label>
                                <div class="form-control-plaintext">
                                    @if($paymentMethod->is_default)
                                        <span class="badge bg-primary">Default</span>
                                    @else
                                        <span class="text-muted">Not default</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-medium text-muted">Sort Order</label>
                                <div class="form-control-plaintext">{{ $paymentMethod->sort_order ?? 0 }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-medium text-muted">Currency</label>
                                <div class="form-control-plaintext">
                                    @if($paymentMethod->getConfig('currency'))
                                        <span class="badge bg-info">{{ $paymentMethod->getConfig('currency') }}</span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-medium text-muted">Test Mode</label>
                                <div class="form-control-plaintext">
                                    @if($paymentMethod->getConfig('test_mode'))
                                        <span class="badge bg-warning">Enabled</span>
                                    @else
                                        <span class="badge bg-success">Live Mode</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gateway Configuration -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="h6 mb-0 fw-bold">
                                <i class="bi bi-shield-lock me-2 text-warning"></i>
                                Gateway Settings
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($paymentMethod->type === 'card' || $paymentMethod->type === 'digital_wallet')
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-muted">Public Key</label>
                                    <div class="form-control-plaintext">
                                        @if($paymentMethod->getConfig('public_key'))
                                            <code class="bg-light px-2 py-1 rounded text-truncate d-block" style="max-width: 200px;" title="{{ $paymentMethod->getConfig('public_key') }}">
                                                {{ Str::limit($paymentMethod->getConfig('public_key'), 30) }}
                                            </code>
                                        @else
                                            <span class="text-muted">Not configured</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-muted">Secret Key</label>
                                    <div class="form-control-plaintext">
                                        @if($paymentMethod->getConfig('secret_key'))
                                            <code class="bg-light px-2 py-1 rounded text-truncate d-block" style="max-width: 200px;" title="Secret key is hidden for security">
                                                ••••••••••••••••••••••••••••••
                                            </code>
                                        @else
                                            <span class="text-muted">Not configured</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-muted">Webhook Secret</label>
                                    <div class="form-control-plaintext">
                                        @if($paymentMethod->getConfig('webhook_secret'))
                                            <code class="bg-light px-2 py-1 rounded text-truncate d-block" style="max-width: 200px;" title="Webhook secret is hidden for security">
                                                ••••••••••••••••••••••••••••••
                                            </code>
                                        @else
                                            <span class="text-muted">Not configured</span>
                                        @endif
                                    </div>
                                </div>
                            @elseif($paymentMethod->type === 'bank_transfer')
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-muted">Bank Name</label>
                                    <div class="form-control-plaintext">{{ $paymentMethod->getConfig('bank_name') ?? 'Not set' }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-muted">Account Name</label>
                                    <div class="form-control-plaintext">{{ $paymentMethod->getConfig('account_name') ?? 'Not set' }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-muted">Account Number</label>
                                    <div class="form-control-plaintext">{{ $paymentMethod->getConfig('account_number') ?? 'Not set' }}</div>
                                </div>
                                
                                @if($paymentMethod->getConfig('sort_code'))
                                    <div class="mb-3">
                                        <label class="form-label fw-medium text-muted">Sort Code</label>
                                        <div class="form-control-plaintext">{{ $paymentMethod->getConfig('sort_code') }}</div>
                                    </div>
                                @endif
                                
                                @if($paymentMethod->getConfig('swift_code'))
                                    <div class="mb-3">
                                        <label class="form-label fw-medium text-muted">SWIFT Code</label>
                                        <div class="form-control-plaintext">{{ $paymentMethod->getConfig('swift_code') }}</div>
                                    </div>
                                @endif
                                
                                @if($paymentMethod->getConfig('instructions'))
                                    <div class="mb-3">
                                        <label class="form-label fw-medium text-muted">Transfer Instructions</label>
                                        <div class="form-control-plaintext">{{ $paymentMethod->getConfig('instructions') }}</div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="d-flex gap-2">
                    @if(!$paymentMethod->is_default)
                        <form action="{{ route('admin.payment-methods.set-default', $paymentMethod) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-star me-2"></i>Set as Default
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.payment-methods.toggle-status', $paymentMethod) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-{{ $paymentMethod->is_active ? 'warning' : 'success' }}">
                            <i class="bi bi-{{ $paymentMethod->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $paymentMethod->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Payment Method
                    </a>
                    <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-event-layout>
