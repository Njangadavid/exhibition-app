<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;
// use App\Helpers\SidebarHelper;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        $event = null;
        $paymentMethods = PaymentMethod::orderBy('sort_order');

        // If event ID is provided, filter by event
        if ($request->has('event')) {
            $event = \App\Models\Event::findOrFail($request->event);
            $paymentMethods = $paymentMethods->where('event_id', $event->id);
        } else {
            // If no event specified, show all payment methods for admin users
            // or redirect to events list if user doesn't have admin access
            if (!auth()->user()->hasRole('admin')) {
                return redirect()->route('events.index')
                    ->with('info', 'Please select an event to manage payment methods.');
            }
        }

        $paymentMethods = $paymentMethods->get();
        // SidebarHelper::expand();

        return view('admin.payment-methods.index', compact('paymentMethods', 'event'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        $event = null;
        if ($request->has('event')) {
            $event = \App\Models\Event::findOrFail($request->event);
        }

        $types = [
            'card' => 'Card Payment',
            'bank_transfer' => 'Bank Transfer',
            'digital_wallet' => 'Digital Wallet',
            'mobile_money' => 'Mobile Money',
            'pesapal' => 'Pesapal',
            'crypto' => 'Cryptocurrency',
        ];
        // SidebarHelper::expand();

        return view('admin.payment-methods.create', compact('types', 'event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100', // Made optional since it's auto-generated
            'type' => 'required|string|in:card,bank_transfer,digital_wallet,mobile_money,pesapal,crypto',
            'gateway' => 'nullable|string|in:paystack,pesapal,manual',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'nullable|boolean',
            'sort_order' => 'integer|min:0',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'config' => 'array',
        ]);

        // Handle checkbox values that might not be present
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;
        $validated['is_default'] = $request->has('is_default') ? (bool) $request->input('is_default') : false;

        // Auto-generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generatePaymentCode($validated['name']);
        }

        // Make code unique per event
        $validated['code'] = $validated['code'] . '_' . $validated['event_id'];

        // If setting as default, unset other defaults for this event
        if ($validated['is_default']) {
            PaymentMethod::where('event_id', $validated['event_id'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        // Handle configuration based on type and gateway
        $config = $this->buildConfig($validated['type'], $request->input('config', []), $validated['gateway']);
        $validated['config'] = $config;

        try {
            PaymentMethod::create($validated);

            Log::info('Payment method created', [
                'name' => $validated['name'],
                'code' => $validated['code'],
                'type' => $validated['type']
            ]);

            return redirect()->route('admin.payment-methods.index', ['event' => $validated['event_id']])
                ->with('success', 'Payment method created successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create payment method', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()->with('error', 'Failed to create payment method. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        // SidebarHelper::expand();

        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        $types = [
            'card' => 'Card Payment',
            'bank_transfer' => 'Bank Transfer',
            'digital_wallet' => 'Digital Wallet',
            'mobile_money' => 'Mobile Money',
            'pesapal' => 'Pesapal',
            'crypto' => 'Cryptocurrency',
        ];

        // Get the event from the payment method
        $event = $paymentMethod->event;

        return view('admin.payment-methods.edit', compact('paymentMethod', 'types', 'event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100|unique:payment_methods,code,' . $paymentMethod->id, // Made optional
            'type' => 'required|string|in:card,bank_transfer,digital_wallet,mobile_money,pesapal,crypto',
            'gateway' => 'nullable|string|in:paystack,pesapal,manual',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'nullable|boolean',
            'sort_order' => 'integer|min:0',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'config' => 'array',
        ]);

        // Handle checkbox values that might not be present
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;
        $validated['is_default'] = $request->has('is_default') ? (bool) $request->input('is_default') : false;

        // Auto-generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generatePaymentCode($validated['name']);
        }

        // Make code unique per event
        $validated['code'] = $validated['code'] . '_' . $validated['event_id'];

        // If setting as default, unset other defaults for the same event
        if ($validated['is_default']) {
            PaymentMethod::where('event_id', $validated['event_id'])
                ->where('is_default', true)
                ->where('id', '!=', $paymentMethod->id)
                ->update(['is_default' => false]);
        }

        // Handle configuration based on type and gateway
        $config = $this->buildConfig($validated['type'], $request->input('config', []), $validated['gateway']);
        $validated['config'] = $config;

        try {
            $paymentMethod->update($validated);

            Log::info('Payment method updated', [
                'id' => $paymentMethod->id,
                'name' => $validated['name'],
                'code' => $validated['code'],
                'event_id' => $validated['event_id']
            ]);

            return redirect()->route('admin.payment-methods.index', ['event' => $validated['event_id']])
                ->with('success', 'Payment method updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update payment method', [
                'id' => $paymentMethod->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()->with('error', 'Failed to update payment method. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        try {
            // Don't allow deletion of default payment method
            if ($paymentMethod->is_default) {
                return back()->with('error', 'Cannot delete the default payment method.');
            }

            $eventId = $paymentMethod->event_id;
            $paymentMethod->delete();

            Log::info('Payment method deleted', [
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name,
                'event_id' => $eventId
            ]);

            return redirect()->route('admin.payment-methods.index', ['event' => $eventId])
                ->with('success', 'Payment method deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete payment method', [
                'id' => $paymentMethod->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to delete payment method. Please try again.');
        }
    }

    /**
     * Toggle payment method status
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        try {
            $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

            $status = $paymentMethod->is_active ? 'activated' : 'deactivated';

            Log::info('Payment method status toggled', [
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name,
                'status' => $status
            ]);

            return back()->with('success', "Payment method {$status} successfully!");
        } catch (\Exception $e) {
            Log::error('Failed to toggle payment method status', [
                'id' => $paymentMethod->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update payment method status.');
        }
    }

    /**
     * Set payment method as default
     */
    public function setDefault(PaymentMethod $paymentMethod)
    {
        // Check if user has permission to manage payment methods
        if (!auth()->user()->hasPermission('manage_payment_methods')) {
            abort(403, 'You do not have permission to manage payment methods.');
        }

        try {
            // Unset current default
            PaymentMethod::where('is_default', true)->update(['is_default' => false]);

            // Set new default
            $paymentMethod->update(['is_default' => true]);

            Log::info('Default payment method changed', [
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name
            ]);

            return back()->with('success', 'Default payment method updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to set default payment method', [
                'id' => $paymentMethod->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update default payment method.');
        }
    }

    /**
     * Build configuration based on payment method type and gateway
     */
    private function buildConfig($type, $config, $gateway = null)
    {
        // If gateway is specified, use gateway-specific configuration
        if ($gateway) {
            switch ($gateway) {
                case 'paystack':
                    return [
                        'public_key' => $config['public_key'] ?? '',
                        'secret_key' => $config['secret_key'] ?? '',
                        'webhook_secret' => $config['webhook_secret'] ?? '',
                        'test_mode' => $config['test_mode'] ?? false,
                        'currency' => $config['currency'] ?? 'NGN',
                        'supported_countries' => $config['supported_countries'] ?? [],
                    ];


                case 'pesapal':
                    return [
                        'consumer_key' => $config['consumer_key'] ?? $config['public_key'] ?? '',
                        'consumer_secret' => $config['consumer_secret'] ?? $config['secret_key'] ?? '',
                        'test_mode' => $config['test_mode'] ?? false,
                        'currency' => $config['currency'] ?? 'KES',
                        // Keep original fields for backward compatibility
                        'public_key' => $config['public_key'] ?? '',
                        'secret_key' => $config['secret_key'] ?? '',
                        'webhook_secret' => $config['webhook_secret'] ?? '',
                        'supported_countries' => $config['supported_countries'] ?? [],
                    ];

                case 'manual':
                    return [
                        'bank_name' => $config['bank_name'] ?? '',
                        'account_name' => $config['account_name'] ?? '',
                        'account_number' => $config['account_number'] ?? '',
                        'sort_code' => $config['sort_code'] ?? '',
                        'swift_code' => $config['swift_code'] ?? '',
                        'currency' => $config['currency'] ?? 'USD',
                        'instructions' => $config['instructions'] ?? '',
                    ];
            }
        }

        // Fallback to type-based configuration
        switch ($type) {
            case 'card':
                return [
                    'public_key' => $config['public_key'] ?? '',
                    'secret_key' => $config['secret_key'] ?? '',
                    'webhook_secret' => $config['webhook_secret'] ?? '',
                    'test_mode' => $config['test_mode'] ?? false,
                    'currency' => $config['currency'] ?? 'USD',
                    'supported_countries' => $config['supported_countries'] ?? [],
                ];

            case 'bank_transfer':
                return [
                    'bank_name' => $config['bank_name'] ?? '',
                    'account_name' => $config['account_name'] ?? '',
                    'account_number' => $config['account_number'] ?? '',
                    'sort_code' => $config['sort_code'] ?? '',
                    'swift_code' => $config['swift_code'] ?? '',
                    'currency' => $config['currency'] ?? 'USD',
                    'instructions' => $config['instructions'] ?? '',
                ];

            case 'digital_wallet':
                return [
                    'client_id' => $config['client_id'] ?? '',
                    'client_secret' => $config['client_secret'] ?? '',
                    'webhook_id' => $config['webhook_id'] ?? '',
                    'test_mode' => $config['test_mode'] ?? false,
                    'currency' => $config['currency'] ?? 'USD',
                    'supported_countries' => $config['supported_countries'] ?? [],
                ];

            case 'pesapal':
                return [
                    'consumer_key' => $config['consumer_key'] ?? '',
                    'consumer_secret' => $config['consumer_secret'] ?? '',
                    'test_mode' => $config['test_mode'] ?? false,
                    'currency' => $config['currency'] ?? 'KES',
                ];

            default:
                return $config;
        }
    }

    /**
     * Generate a unique payment code.
     */
    private function generatePaymentCode($name)
    {
        // Convert name to lowercase and replace spaces with underscores
        $slug = \Illuminate\Support\Str::slug($name);

        // Add a timestamp to make it unique
        $timestamp = now()->timestamp;

        // Combine slug and timestamp
        return "{$slug}_{$timestamp}";
    }
}
