<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\FloorplanItem;
use App\Models\Booking;
use App\Models\FormBuilder;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Show the owner registration form with access token (for existing bookings).
     */
    public function showOwnerFormWithToken(Request $request, $eventSlug, $accessToken)
    {
        // Find the event
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        
        // Find the existing booking by access token
        $booking = $event->bookings()
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();
        
        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Access token has expired or is invalid.');
        }
        
        // Get the floorplan item
        $item = $booking->floorplanItem;
        if (!$item) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Floorplan item not found.');
        }
        
        // Check if this is a new booking or editing existing
        $isEditing = true;
        $existingBooking = $booking;
        
        return view('bookings.owner-form', compact('event', 'item', 'isEditing', 'existingBooking', 'booking'));
    }

    /**
     * Show the owner registration form for booking.
     */
    public function showOwnerForm(Request $request, $eventSlug, $itemId)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $item = FloorplanItem::findOrFail($itemId);
        
        // Check if item is available for booking (only for new bookings)
        $currentBookingId = session('current_booking_id');
        if (!$currentBookingId) {
            // Only check availability for new bookings
            $existingBooking = Booking::where('floorplan_item_id', $itemId)
                ->where('event_id', $event->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();
            
            if ($existingBooking) {
                return redirect()->back()->with('error', 'This item is already booked or reserved.');
            }
        }
        
        // Check if there's an existing booking for this item
        $existingBooking = Booking::where('floorplan_item_id', $itemId)
            ->where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->first();
        
        // Determine if we're editing an existing booking
        $isEditing = false;
        $currentBookingId = session('current_booking_id');
        
        if ($existingBooking) {
            // If there's an existing booking, check if it's the current session booking
            if ($currentBookingId && $existingBooking->id == $currentBookingId) {
                $isEditing = true;
            } else {
                // If there's a booking but no session, this might be a return visit
                // We'll allow them to edit their existing booking
                $isEditing = true;
                // Set the session to this booking
                session(['current_booking_id' => $existingBooking->id]);
            }
        }
        
        return view('bookings.owner-form', compact('event', 'item', 'existingBooking', 'isEditing'));
    }

    /**
     * Process owner registration and create booking.
     */
    public function processOwnerForm(Request $request, $eventSlug, $itemId)
    {
        Log::info('=== OWNER FORM PROCESSING START ===', [
            'event_slug' => $eventSlug,
            'item_id' => $itemId,
            'session_booking_id' => session('current_booking_id'),
            'request_data' => $request->only(['owner_name', 'owner_email', 'company_name'])
        ]);

        $request->validate([
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_website' => 'nullable|url|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
        ]);

        Log::info('Validation passed');

        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $item = FloorplanItem::findOrFail($itemId);
        
        Log::info('Event and item loaded', [
            'event_id' => $event->id,
            'item_id' => $item->id,
            'item_label' => $item->label
        ]);
        
        // Check if there's already a booking for this item (for new bookings only)
        $currentBookingId = session('current_booking_id');
        Log::info('Checking booking availability', [
            'current_booking_id' => $currentBookingId,
            'is_new_booking' => !$currentBookingId
        ]);
        
        if (!$currentBookingId) {
            // Only check availability for new bookings
            $existingBooking = Booking::where('floorplan_item_id', $itemId)
                ->where('event_id', $event->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();
            
            Log::info('Availability check result', [
                'existing_booking_found' => $existingBooking ? true : false,
                'existing_booking_id' => $existingBooking?->id
            ]);
            
            if ($existingBooking) {
                Log::warning('Redirecting back - item already booked');
                return redirect()->back()->with('error', 'This item is already booked or reserved.');
            }
        }
        
        try {
            DB::beginTransaction();
            Log::info('Transaction started');
            
            // Check if we're updating an existing booking
            $existingBooking = null;
            $currentBookingId = session('current_booking_id');
            
            Log::info('Looking for existing booking', [
                'current_booking_id' => $currentBookingId
            ]);
            
            if ($currentBookingId) {
                $existingBooking = Booking::where('id', $currentBookingId)
                    ->where('floorplan_item_id', $itemId)
                    ->where('event_id', $event->id)
                    ->first();
                    
                Log::info('Existing booking search result', [
                    'booking_found' => $existingBooking ? true : false,
                    'booking_id' => $existingBooking?->id
                ]);
                    
                if (!$existingBooking) {
                    // Session booking ID doesn't match, clear session and treat as new
                    Log::warning('Session booking ID invalid, clearing session');
                    session()->forget('current_booking_id');
                    $currentBookingId = null;
                }
            }
            
            // Handle logo upload if provided
            $logoPath = null;
            if ($request->hasFile('company_logo')) {
                $logoPath = $request->file('company_logo')->store('company-logos', 'public');
            } elseif ($existingBooking && isset($existingBooking->owner_details['company_logo'])) {
                // Keep existing logo if no new one uploaded
                $logoPath = $existingBooking->owner_details['company_logo'];
            }

            if ($existingBooking) {
                Log::info('Updating existing booking', [
                    'booking_id' => $existingBooking->id,
                    'booking_reference' => $existingBooking->booking_reference
                ]);
                
                // Update existing booking
                $existingBooking->update([
                    'owner_details' => [
                        'name' => $request->owner_name,
                        'email' => $request->owner_email,
                        'phone' => $request->owner_phone,
                        'company_name' => $request->company_name,
                        'company_address' => $request->company_address,
                        'company_website' => $request->company_website,
                        'company_logo' => $logoPath,
                        'social_facebook' => $request->social_facebook,
                        'social_twitter' => $request->social_twitter,
                        'social_linkedin' => $request->social_linkedin,
                        'social_instagram' => $request->social_instagram,
                    ],
                ]);

                // Generate access token if not exists
                if (!$existingBooking->access_token) {
                    $existingBooking->refreshAccessToken();
                }
                
                $booking = $existingBooking;
                
                Log::info('Existing booking updated successfully');
            } else {
                Log::info('Creating new booking');
                
                // Create new booking
                $booking = Booking::create([
                    'event_id' => $event->id,
                    'floorplan_item_id' => $item->id,
                    'booking_reference' => Booking::generateReference(),
                    'access_token' => Booking::generateAccessToken(),
                    'access_token_expires_at' => now()->addYear(),
                    'status' => 'pending',
                    'total_amount' => $item->price,
                    'owner_details' => [
                        'name' => $request->owner_name,
                        'email' => $request->owner_email,
                        'phone' => $request->owner_phone,
                        'company_name' => $request->company_name,
                        'company_address' => $request->company_address,
                        'company_website' => $request->company_website,
                        'company_logo' => $logoPath,
                        'social_facebook' => $request->social_facebook,
                        'social_twitter' => $request->social_twitter,
                        'social_linkedin' => $request->social_linkedin,
                        'social_instagram' => $request->social_instagram,
                    ],
                    'booking_date' => now(),
                ]);
                
                Log::info('New booking created', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference
                ]);
                
            }
            
            DB::commit();
            Log::info('Transaction committed successfully');
            
            Log::info('Redirecting to member form', [
                'route' => 'bookings.member-form',
                'event_slug' => $eventSlug,
                'access_token' => $booking->access_token
            ]);
            
            // Redirect to member registration using access token
            return redirect()->route('bookings.member-form', [
                'eventSlug' => $eventSlug,
                'accessToken' => $booking->access_token
            ]);
            
        } catch (\Exception $e) {
            Log::error('Exception in processOwnerForm', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            DB::rollBack();
            Log::info('Transaction rolled back due to exception');
            
            return redirect()->back()->with('error', 'Failed to save booking. Please try again.');
        }
        
        Log::warning('Reached end of processOwnerForm without return - this should not happen');
    }

    /**
     * Show the member registration form.
     */
    public function showMemberForm($eventSlug, $accessToken)
    {
        Log::info('=== MEMBER FORM LOADING START ===', [
            'event_slug' => $eventSlug,
            'access_token' => $accessToken
        ]);
        
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::where('access_token', $accessToken)->firstOrFail();
        
        Log::info('Event and booking loaded', [
            'event_id' => $event->id,
            'booking_reference' => $booking->booking_reference
        ]);
        
        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            Log::warning('Invalid or expired access token', [
                'access_token' => $accessToken,
                'booking_id' => $booking->id
            ]);
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }
        
        Log::info('Access token verification passed');
        
        // Get the member registration form for this event
        // First try to find a member_registration form, then fall back to any form
        $memberForm = FormBuilder::where('event_id', $event->id)
            ->where('type', 'member_registration')
            ->first();
            
        if (!$memberForm) {
            // Fallback: use any available form for this event
            $memberForm = FormBuilder::where('event_id', $event->id)->first();
            Log::info('No member_registration form found, using fallback form', [
                'fallback_form_id' => $memberForm?->id,
                'fallback_form_name' => $memberForm?->name,
                'fallback_form_type' => $memberForm?->type
            ]);
        }
            
        Log::info('Member form search result', [
            'form_found' => $memberForm ? true : false,
            'form_id' => $memberForm?->id,
            'form_name' => $memberForm?->name,
            'form_type' => $memberForm?->type
        ]);
            
        if (!$memberForm) {
            Log::warning('No forms found for this event - redirecting back');
            return redirect()->back()->with('error', 'No registration forms found for this event. Please contact the organizer.');
        }
        
        Log::info('Member form view loading successfully');
        return view('bookings.member-form', compact('event', 'booking', 'memberForm'));
    }

    /**
     * Process member registration.
     */
    public function processMemberForm(Request $request, $eventSlug, $accessToken)
    {
        $request->validate([
            'form_data' => 'required|array',
        ]);

        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::where('access_token', $accessToken)->firstOrFail();
        
        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }
        
        try {
            // Update booking with member details
            $booking->update([
                'member_details' => $request->form_data,
            ]);
            
            // Store member form submission
            FormSubmission::create([
                'form_builder_id' => $request->member_form_id,
                'submission_data' => $request->form_data,
                'submitted_at' => now(),
            ]);
            
            // Redirect to payment
            return redirect()->route('bookings.payment', [
                'eventSlug' => $eventSlug,
                'accessToken' => $booking->access_token
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save member details. Please try again.');
        }
    }

    /**
     * Save members to booking (AJAX endpoint).
     */
    public function saveMembers(Request $request, $eventSlug, $accessToken)
    {
        $request->validate([
            'member_details' => 'required|string', // JSON string
        ]);

        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::where('access_token', $accessToken)->firstOrFail();
        
        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired access link. Please start over.'
            ], 400);
        }
        
        try {
            // Decode JSON member details
            $memberDetails = json_decode($request->member_details, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid member data format.'
                ], 400);
            }
            
            // Update booking with member details
            $booking->update([
                'member_details' => $memberDetails,
            ]);
            
            Log::info('Members saved successfully', [
                'booking_id' => $booking->id,
                'member_count' => count($memberDetails),
                'event_slug' => $eventSlug
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Members saved successfully!',
                'member_count' => count($memberDetails)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to save members', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'event_slug' => $eventSlug
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save members. Please try again.'
            ], 500);
        }
    }

    /**
     * Show payment page.
     */
    public function showPayment($eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::where('access_token', $accessToken)->firstOrFail();
        
        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }
        
        return view('bookings.payment', compact('event', 'booking'));
    }

    /**
     * Process payment for booking.
     */
    public function processPayment(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::where('access_token', $accessToken)->firstOrFail();
        
        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }

        try {
            // Get the default payment method for this event
            $paymentMethod = $event->paymentMethods->where('is_active', true)->where('is_default', true)->first();
            
            if (!$paymentMethod) {
                return back()->with('error', 'No payment method available for this event.');
            }

            // Check if it's a Paystack payment method
            if ($paymentMethod->code === 'paystack') {
                return $this->processPaystackPayment($request, $event, $booking, $paymentMethod);
            }

            // For other payment methods, use the old logic
            return $this->processLegacyPayment($request, $event, $booking);

        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'event_slug' => $eventSlug
            ]);

            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Process Paystack payment
     */
    protected function processPaystackPayment($request, $event, $booking, $paymentMethod)
    {
        try {
            $paystackService = app(\App\Services\PaystackService::class);
            
            if (!$paystackService->isConfigured()) {
                return back()->with('error', 'Paystack payment is not configured. Please contact support.');
            }

            // Generate unique reference
            $reference = 'BK_' . $booking->id . '_' . time();
            
            // Initialize Paystack transaction
            $transactionData = [
                'amount' => $booking->floorplanItem->price,
                'email' => $booking->owner_details['email'],
                'reference' => $reference,
                'callback_url' => route('bookings.paystack.callback', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]),
                'currency' => 'NGN', // Paystack default currency
                'metadata' => [
                    'booking_id' => $booking->id,
                    'event_id' => $event->id,
                    'space_label' => $booking->floorplanItem->label,
                    'owner_name' => $booking->owner_details['name'],
                ],
            ];

            $transaction = $paystackService->initializeTransaction($transactionData);

            if ($transaction->status) {
                // Store payment record
                $payment = new \App\Models\Payment();
                $payment->booking_id = $booking->id;
                $payment->amount = $booking->floorplanItem->price;
                $payment->currency = 'NGN';
                $payment->payment_method = 'paystack';
                $payment->gateway = 'paystack';
                $payment->gateway_transaction_id = $reference;
                $payment->status = 'pending';
                $payment->gateway_response = json_encode($transaction);
                $payment->save();

                // Update booking status to pending
                $booking->status = 'pending';
                $booking->save();

                Log::info('Paystack transaction initialized', [
                    'booking_id' => $booking->id,
                    'reference' => $reference,
                    'authorization_url' => $transaction->data->authorization_url
                ]);

                // Redirect to Paystack payment page
                return redirect($transaction->data->authorization_url);

            } else {
                throw new \Exception('Failed to initialize Paystack transaction: ' . $transaction->message);
            }

        } catch (\Exception $e) {
            Log::error('Paystack payment initialization failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }
    }

    /**
     * Process legacy payment methods
     */
    protected function processLegacyPayment($request, $event, $booking)
    {
        // Validate payment data
        $validated = $request->validate([
            'payment_method' => 'required|in:card,bank_transfer',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'billing_state' => 'required|string|max:100',
            'billing_country' => 'required|string|max:100',
            'terms_agreed' => 'required|accepted',
            // Card payment fields (conditional)
            'card_number' => 'required_if:payment_method,card|string|max:19',
            'expiry_date' => 'required_if:payment_method,card|string|size:5',
            'cvv' => 'required_if:payment_method,card|string|min:3|max:4',
            'cardholder_name' => 'required_if:payment_method,card|string|max:255',
        ]);

        Log::info('Processing legacy payment', [
            'booking_id' => $booking->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $booking->floorplanItem->price,
            'event_slug' => $event->slug
        ]);

        try {
            // Create payment record
            $payment = new \App\Models\Payment();
            $payment->booking_id = $booking->id;
            $payment->amount = $booking->floorplanItem->price;
            $payment->currency = 'USD';
            $payment->payment_method = $validated['payment_method'];
            $payment->billing_address = json_encode([
                'address' => $validated['billing_address'],
                'city' => $validated['billing_address'],
                'postal_code' => $validated['billing_postal_code'],
                'state' => $validated['billing_state'],
                'country' => $validated['billing_country'],
            ]);

            if ($validated['payment_method'] === 'card') {
                // For now, simulate card processing
                $payment->gateway = 'demo';
                $payment->gateway_transaction_id = 'demo_' . time() . '_' . $booking->id;
                $payment->status = 'completed';
                $payment->gateway_response = json_encode([
                    'status' => 'success',
                    'message' => 'Payment processed successfully (demo)',
                    'card_last4' => substr(str_replace(' ', '', $validated['card_number']), -4),
                    'cardholder_name' => $validated['cardholder_name'],
                ]);
                
                // Update booking status to confirmed
                $booking->status = 'confirmed';
                $booking->save();

            } else {
                // Bank transfer - set as pending
                $payment->gateway = 'bank_transfer';
                $payment->status = 'pending';
                $payment->gateway_response = json_encode([
                    'status' => 'pending',
                    'message' => 'Awaiting bank transfer confirmation',
                    'reference' => 'BOOKING-' . $booking->id,
                ]);
                
                // Keep booking status as pending until transfer is confirmed
                $booking->status = 'pending';
                $booking->save();
            }

            $payment->save();

            // Clear booking session
            session()->forget('current_booking_id');

            Log::info('Legacy payment processed successfully', [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'event_slug' => $event->slug
            ]);

            // Redirect to success page with appropriate message
            if ($validated['payment_method'] === 'card') {
                return redirect()->route('bookings.success', ['eventSlug' => $event->slug, 'bookingId' => $booking->id])
                    ->with('success', 'Payment successful! Your booking has been confirmed.');
            } else {
                return redirect()->route('bookings.success', ['eventSlug' => $event->slug, 'bookingId' => $booking->id])
                    ->with('info', 'Booking created! Please complete the bank transfer to confirm your booking.');
            }

        } catch (\Exception $e) {
            Log::error('Legacy payment processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'event_slug' => $event->slug
            ]);

            return back()->with('error', 'Payment processing failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Handle Paystack payment callback
     */
    public function paystackCallback(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::where('access_token', $accessToken)->firstOrFail();
        
        try {
            $paystackService = app(\App\Services\PaystackService::class);
            
            // Verify the transaction
            $reference = $request->query('reference');
            if (!$reference) {
                throw new \Exception('No reference provided in callback');
            }

            $transaction = $paystackService->verifyTransaction($reference);
            
            if ($transaction->status && $transaction->data->status === 'success') {
                // Payment successful
                $payment = \App\Models\Payment::where('gateway_transaction_id', $reference)->first();
                
                if ($payment) {
                    $payment->status = 'completed';
                    $payment->gateway_response = json_encode($transaction);
                    $payment->save();
                }

                // Update booking status
                $booking->status = 'confirmed';
                $booking->save();

                                   Log::info('Paystack payment successful', [
                       'booking_id' => $booking->id,
                       'reference' => $reference,
                       'amount' => $transaction->data->amount / 100
                   ]);
   
                   return redirect()->route('bookings.success', ['eventSlug' => $eventSlug, 'accessToken' => $booking->access_token])
                       ->with('success', 'Payment successful! Your booking has been confirmed.');

            } else {
                // Payment failed
                $payment = \App\Models\Payment::where('gateway_transaction_id', $reference)->first();
                
                if ($payment) {
                    $payment->status = 'failed';
                    $payment->gateway_response = json_encode($transaction);
                    $payment->save();
                }

                Log::warning('Paystack payment failed', [
                    'booking_id' => $booking->id,
                    'reference' => $reference,
                    'response' => $transaction
                ]);

                return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $booking->access_token])
                    ->with('error', 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Paystack callback processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $booking->access_token])
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /**
     * Show booking success page.
     */
    public function showSuccess($eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = Booking::with(['floorplanItem', 'payments'])->where('access_token', $accessToken)->firstOrFail();
        
        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }
        
        return view('bookings.success', compact('event', 'booking'));
    }

    /**
     * Process owner form submission for existing bookings (with access token)
     */
    public function processOwnerFormWithToken(Request $request, $eventSlug, $accessToken)
    {
        // Find the event
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        
        // Find the existing booking by access token
        $booking = $event->bookings()
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();
        
        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Access token has expired or is invalid.');
        }
        
        // Validate the request
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_website' => 'nullable|url|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('company_logo')) {
                $logoPath = $request->file('company_logo')->store('company-logos', 'public');
            } else {
                // Keep existing logo if no new one uploaded
                $logoPath = $booking->owner_details['company_logo'] ?? null;
            }
            
            // Update existing booking
            $booking->update([
                'owner_details' => [
                    'name' => $request->owner_name,
                    'email' => $request->owner_email,
                    'phone' => $request->owner_phone,
                    'company_name' => $request->company_name,
                    'company_address' => $request->company_address,
                    'company_website' => $request->company_website,
                    'company_logo' => $logoPath,
                    'social_facebook' => $request->social_facebook,
                    'social_twitter' => $request->social_twitter,
                    'social_linkedin' => $request->social_linkedin,
                    'social_instagram' => $request->social_instagram,
                ],
            ]);
            
            DB::commit();
            
            Log::info('Owner details updated successfully for existing booking', [
                'booking_id' => $booking->id,
                'access_token' => $accessToken
            ]);
            
            // Redirect to member form using access token
            return redirect()->route('bookings.member-form', [
                'eventSlug' => $eventSlug,
                'accessToken' => $accessToken
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update owner details for existing booking', [
                'access_token' => $accessToken,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to update owner details. Please try again.');
        }
    }

    /**
     * Remove a booking (for changing spaces)
     */
    public function removeBooking(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = $event->bookings()
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();
        
        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Access token has expired or is invalid.'
            ], 400);
        }
        
        try {
            // Delete the booking
            $booking->delete();
            
            Log::info('Booking removed for space change', [
                'event_slug' => $eventSlug,
                'booking_id' => $booking->id,
                'floorplan_item_id' => $booking->floorplan_item_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Booking removed successfully. You can now select a new space.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to remove booking', [
                'event_slug' => $eventSlug,
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove booking. Please try again.'
            ], 500);
        }
    }
}
