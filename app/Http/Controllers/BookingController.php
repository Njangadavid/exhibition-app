<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\FloorplanItem;
use App\Models\Booking;
use App\Models\FormBuilder;
use App\Models\FormSubmission;
use App\Services\EmailCommunicationService;
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

        // Find the booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();
        
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

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

        return view('bookings.owner-form', compact('event', 'item', 'isEditing', 'boothOwner', 'booking'));
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
                ->whereIn('status', ['reserved', 'booked'])
                ->first();

            if ($existingBooking) {
                return redirect()->back()->with('error', 'This item is already booked or reserved.');
            }
        }

        // Check if there's an existing booking for this item
        $existingBooking = Booking::where('floorplan_item_id', $itemId)
            ->where('event_id', $event->id)
            ->whereIn('status', ['reserved', 'booked'])
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

        // Check if there's already a booking for this item by someone else
        $currentBookingId = session('current_booking_id');
        Log::info('Checking booking availability', [
            'current_booking_id' => $currentBookingId,
            'is_new_booking' => !$currentBookingId
        ]);

        // Check if item is booked by someone else (not the current user)
        $otherUserBooking = null;
        if (!$currentBookingId) {
            // Only check for other users' bookings when starting fresh
            $otherUserBooking = Booking::where('floorplan_item_id', $itemId)
                ->where('event_id', $event->id)
                ->whereIn('status', ['reserved', 'booked'])
                ->first();

            Log::info('Other user booking check result', [
                'other_booking_found' => $otherUserBooking ? true : false,
                'other_booking_id' => $otherUserBooking?->id
            ]);
        } else {
            // Check if current user's session booking is still valid
            $otherUserBooking = Booking::where('floorplan_item_id', $itemId)
                ->where('event_id', $event->id)
                ->whereIn('status', ['reserved', 'booked'])
                ->where('id', '!=', $currentBookingId) // Exclude current user's booking
                ->first();

            Log::info('Current user session validation', [
                'other_booking_found' => $otherUserBooking ? true : false,
                'other_booking_id' => $otherUserBooking?->id
            ]);
        }

        if ($otherUserBooking) {
            Log::warning('Redirecting back - item booked by another user');
            return redirect()->back()->with('error', 'This item is already booked or reserved by another user.');
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
                if (!$existingBooking->boothOwner || !$existingBooking->boothOwner->access_token) {
                    $existingBooking->refreshAccessToken();
                }

                $booking = $existingBooking;

                Log::info('Existing booking updated successfully');
            } else {
                Log::info('Creating new booking');

                // Create booth owner first with access token
                $boothOwner = \App\Models\BoothOwner::create([
                    'qr_code' => \App\Models\BoothOwner::generateQrCode(),
                    'form_responses' => [
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
                    'access_token' => \App\Models\BoothOwner::generateAccessToken(),
                    'access_token_expires_at' => now()->addYear(),
                ]);

                // Create new booking with booth owner reference
                $booking = Booking::create([
                    'event_id' => $event->id,
                    'floorplan_item_id' => $item->id,
                    'booking_reference' => Booking::generateReference(),
                    'booth_owner_id' => $boothOwner->id,
                    'status' => 'reserved',
                    'total_amount' => $item->price ?? 0.00,
                    'booking_date' => now(),
                ]);

                Log::info('New booking created', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'Owner _id' => $boothOwner->id,
                ]);
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            // Send owner registration email if this is a new booking
            if (!$existingBooking) {
                try {
                    $emailService = app(EmailCommunicationService::class);
                    $emailService->sendTriggeredEmail('owner_registration', $booking);
                    Log::info('Owner registration email triggered', [
                        'booking_id' => $booking->id,
                        'trigger_type' => 'owner_registration'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send owner registration email', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the booking process if email fails
                }
            }
            Log::info('Redirecting to member form', [
                'route' => 'bookings.member-form',
                'event_slug' => $eventSlug,
                'access_token' => $booking->boothOwner->access_token
            ]);

            // Redirect to member registration using access token
            return redirect()->route('bookings.member-form', [
                'eventSlug' => $eventSlug,
                'accessToken' => $booking->boothOwner->access_token
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
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            Log::error('Booth owner found but no booking associated', [
                'booth_owner_id' => $boothOwner->id,
                'access_token' => $accessToken
            ]);
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

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

        // Load booth members for display
        $boothMembers = $boothOwner->boothMembers;
        
        Log::info('Member form view loading successfully', [
            'booking_id' => $booking->id,
            'booth_members_count' => $boothMembers->count(),
            'booth_members' => $boothMembers->map(function($member) {
                return [
                    'id' => $member->id,
                    'qr_code' => $member->qr_code,
                    'status' => $member->status,
                    'form_responses' => $member->form_responses
                ];
            })
        ]);
        
        return view('bookings.member-form', compact('event', 'booking', 'memberForm', 'boothMembers'));
    }

    /**
     * Process member registration.
     */
//    public function processMemberForm(Request $request, $eventSlug, $accessToken)
//    {
//        $request->validate([
//            'form_data' => 'required|array',
//        ]);
//
//        $event = Event::where('slug', $eventSlug)->firstOrFail();
//        
//        // Find booth owner by access token, then get the booking
//        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
//        $booking = $boothOwner->booking;
//        
//        if (!$booking) {
//            return redirect()->route('events.public.floorplan', $eventSlug)
//                ->with('error', 'No booking found for this access token. Please start over.');
//        }
//
//        // Verify access token is valid
//        if (!$booking->isAccessTokenValid()) {
//            return redirect()->route('events.public.floorplan', $eventSlug)
//                ->with('error', 'Invalid or expired access link. Please start over.');
//        }
//
//        try {
//            // Store member form submission
//            FormSubmission::create([
//                'form_builder_id' => $request->member_form_id,
//                'submission_data' => $request->form_data,
//                'submitted_at' => now(),
//            ]);
//
//            // Ensure we have a booth owner
//            if (!$booking->boothOwner) {
//                // Create booth owner from existing owner_details
//                $ownerDetails = $booking->owner_details ?? [];
//                $boothOwner = \App\Models\BoothOwner::create([
//                    'qr_code' => \App\Models\BoothOwner::generateQrCode(),
//                    'form_responses' => $ownerDetails,
//                    'access_token' => \App\Models\BoothOwner::generateAccessToken(),
//                    'access_token_expires_at' => now()->addYear(),
//                ]);
//                $booking->update(['booth_owner_id' => $boothOwner->id]);
//            }
//
//            // Create booth member
//            $boothMember = \App\Models\BoothMember::create([
//                'booth_owner_id' => $booking->boothOwner->id,
//                'qr_code' => \App\Models\BoothMember::generateQrCode(),
//                'form_responses' => $request->form_data,
//                'status' => 'active'
//            ]);
//
//            Log::info('Booth member created successfully', [
//                'booking_id' => $booking->id,
//                'member_id' => $boothMember->id,
//                'member_data' => $request->form_data
//            ]);
//
//            // Note: Member registration email is now handled in saveMembers method
//            // to avoid duplicate email sending
//            Log::info('Member added successfully, email will be sent via saveMembers', [
//                'booking_id' => $booking->id,
//                'member_id' => $boothMember->id,
//                'trigger_type' => 'member_registration'
//            ]);
//
//            // Redirect to payment using booth owner's access token
//            return redirect()->route('bookings.payment', [
//                'eventSlug' => $eventSlug,
//                'accessToken' => $booking->boothOwner->access_token
//            ]);
//        } catch (\Exception $e) {
//            return redirect()->back()->with('error', 'Failed to save member details. Please try again.');
//        }
//    }

    /**
     * Save members to booking (AJAX endpoint).
     */
    public function saveMembers(Request $request, $eventSlug, $accessToken)
    {
        $request->validate([
            'member_details' => 'required|string', // JSON string
        ]);

        $event = Event::where('slug', $eventSlug)->firstOrFail();
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'No booking found for this access token. Please start over.'
            ], 400);
        }

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

            // Ensure we have a booth owner
            if (!$booking->boothOwner) {
                // Create booth owner from existing owner_details
                $ownerDetails = $booking->owner_details ?? [];
                $boothOwner = \App\Models\BoothOwner::create([
                    'qr_code' => \App\Models\BoothOwner::generateQrCode(),
                    'form_responses' => $ownerDetails
                ]);
                $booking->update(['booth_owner_id' => $boothOwner->id]);
            }

            // Check if resend email was requested
            $resendEmail = false;
            $resendMemberData = null;
            if (request()->has('resend_member_email') && request()->input('resend_member_email') == '1') {
                $resendEmail = true;
                // Find the member being edited (assuming it's the first one for now)
                // In a real implementation, you'd need to identify which specific member
                $resendMemberData = $memberDetails[0] ?? null;
            }

            // Get existing members to compare
            $existingMembers = $booking->boothOwner->boothMembers()->get();
            $existingEmails = $existingMembers->pluck('form_responses.email')->filter()->toArray();
            
            // Process only new members (not already in database)
            $newMembers = [];
            $updatedMembers = [];
            $membersToKeep = [];
            
            // First, identify which members should be kept (from incoming data)
            foreach ($memberDetails as $memberData) {
                $memberEmail = $memberData['email'] ?? null;
                if ($memberEmail) {
                    $membersToKeep[] = $memberEmail;
                }
            }
            
            // Remove members that are no longer in the list (deleted members)
            foreach ($existingMembers as $existingMember) {
                $existingEmail = $existingMember->form_responses['email'] ?? null;
                if ($existingEmail && !in_array($existingEmail, $membersToKeep)) {
                    Log::info('Deleting member', [
                        'member_id' => $existingMember->id,
                        'email' => $existingEmail
                    ]);
                    $existingMember->delete();
                }
            }
            
            // Now process the remaining members
            foreach ($memberDetails as $memberData) {
                $memberEmail = $memberData['email'] ?? null;
                
                if ($memberEmail && in_array($memberEmail, $existingEmails)) {
                    // Update existing member
                    $existingMember = $existingMembers->first(function($member) use ($memberEmail) {
                        return ($member->form_responses['email'] ?? '') === $memberEmail;
                    });
                    
                    if ($existingMember) {
                        $existingMember->update([
                            'form_responses' => $memberData,
                            'status' => 'active'
                        ]);
                        $updatedMembers[] = $existingMember;
                    }
                } else {
                    // This is a new member
                    $newMembers[] = $memberData;
                }
            }
            
            // Create new members
            foreach ($newMembers as $memberData) {
                \App\Models\BoothMember::create([
                    'booth_owner_id' => $booking->boothOwner->id,
                    'qr_code' => \App\Models\BoothMember::generateQrCode(),
                    'form_responses' => $memberData,
                    'status' => 'active'
                ]);
            }

            // Send member registration email trigger for new members or when resend requested
            $emailService = app(EmailCommunicationService::class);
            
            // Send email for each new member
            foreach ($newMembers as $memberData) {
                try {
                    $emailService->sendTriggeredEmail('member_registration', $booking, $memberData);
                    Log::info('Member registration email triggered for new member', [
                        'booking_id' => $booking->id,
                        'trigger_type' => 'member_registration',
                        'member_data' => $memberData,
                        'member_email' => $memberData['email'] ?? 'unknown'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send member registration email for new member', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the member saving process if email fails
                }
            }
            
            // Send email for updated member if resend requested
            if ($resendEmail && $resendMemberData) {
                try {
                    $emailService->sendTriggeredEmail('member_registration', $booking, $resendMemberData);
                    Log::info('Member registration email triggered via AJAX (resend requested)', [
                        'booking_id' => $booking->id,
                        'trigger_type' => 'member_registration',
                        'member_data' => $resendMemberData
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send member registration email via AJAX', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the member saving process if email fails
                }
            }

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
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

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
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

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

            if (!$paystackService->isConfiguredWithPaymentMethod($paymentMethod)) {
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
                'currency' => 'USD', // Use USD for international compatibility
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
                $payment->currency = 'USD';
                $payment->payment_method = 'paystack';
                $payment->gateway = 'paystack';
                $payment->gateway_transaction_id = $reference;
                $payment->status = 'pending';
                $payment->gateway_response = json_encode($transaction);
                $payment->save();

                // Update booking status to reserved
                $booking->status = 'reserved';
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

                // Update booking status to booked
                $booking->status = 'booked';
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
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

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
                $booking->status = 'booked';
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
     * Show success page after payment.
     */
    public function showSuccess($eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }

        // Verify that a successful payment exists
        if (!$booking->hasCompletedPayments()) {
            return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $accessToken])
                ->with('error', 'No successful payment found. Please complete your payment first.');
        }

        // Set current step to 5 (Receipt) and show progress
        $currentStep = 5;
        $showProgress = true;

        return view('bookings.success', compact('event', 'booking', 'currentStep', 'showProgress'));
    }

    /**
     * Process owner form submission for existing bookings (with access token)
     */
    public function processOwnerFormWithToken(Request $request, $eventSlug, $accessToken)
    {
        // Find the event
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        // Find the booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();
        
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

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

            // Send owner registration email again if user opted in
            if ($request->has('resend_email') && $request->resend_email == '1') {
                try {
                    $emailService = app(EmailCommunicationService::class);
                    $emailService->sendTriggeredEmail('owner_registration', $booking);
                    Log::info('Owner registration email re-sent after update', [
                        'booking_id' => $booking->id,
                        'trigger_type' => 'owner_registration',
                        'reason' => 'user_requested_resend'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to re-send owner registration email after update', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the update process if email fails
                }
            }

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
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();
        
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'No booking found for this access token. Please start over.'
            ], 400);
        }

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

    /**
     * Public floorplan view (no authentication required).
     */
    public function publicFloorplan(Event $event, Request $request)
    {
        // Check if event is published or active
        if (!in_array($event->status, ['published', 'active'])) {
            abort(404, 'Event not found.');
        }

        // Check if user has an existing booking via access token
        $existingBooking = null;
        $accessToken = $request->query('access_token');

        if ($accessToken) {
            $existingBooking = $event->bookings()
                ->where('access_token', $accessToken)
                ->where('access_token_expires_at', '>', now())
                ->with(['floorplanItem'])
                ->first();
        }

        // Load floorplan data with items if exists
        $floorplanDesign = $event->floorplanDesign;
        if ($floorplanDesign) {
            $floorplanDesign->load('items');

            // Load booking information for each item to determine availability
            $items = $floorplanDesign->items;
            foreach ($items as $item) {
                // Check if there's an active booking for this item
                $activeBooking = $item->bookings()
                    ->whereIn('status', ['reserved', 'booked'])
                    ->with('payments')
                    ->latest()
                    ->first();

                // Add booking status to item for frontend use
                if ($activeBooking) {
                    // Determine status based on booking status
                    $item->booking_status = $activeBooking->status;


                    $item->booking_reference = $activeBooking->booking_reference;
                    $item->payment_status = $activeBooking->payment_status;
                    $item->total_paid = $activeBooking->total_paid;
                    $item->remaining_amount = $activeBooking->remaining_amount;

                    // Add owner details for company information display
                    $item->owner_details = $activeBooking->owner_details;

                    // Check if this is the user's current booking
                    if ($existingBooking && $existingBooking->floorplan_item_id == $item->id) {
                        $item->is_current_booking = true;
                        $item->current_booking_status = $existingBooking->status;
                        $item->current_booking_progress = $this->getBookingProgress($existingBooking);
                    }
                } else {
                    $item->booking_status = 'available';
                    $item->booking_reference = null;
                    $item->payment_status = null;
                    $item->total_paid = 0;
                    $item->remaining_amount = 0;
                }
            }
        }

        // Set $booking for the layout to use (when access token exists)
        $booking = $existingBooking;

        return view('bookings.floorplan', compact('event', 'floorplanDesign', 'existingBooking', 'accessToken', 'booking'));
    }

    /**
     * Public floorplan view with access token in route (for existing bookings)
     */
    public function publicFloorplanWithToken(Event $event, $accessToken)
    {
        // Check if event is published or active
        if (!in_array($event->status, ['published', 'active'])) {
            abort(404, 'Event not found.');
        }

        // Find existing booking via access token
        $existingBooking = $event->bookings()
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->with(['floorplanItem'])
            ->first();

        if (!$existingBooking) {
            abort(404, 'Invalid or expired access link.');
        }

        // Load floorplan data with items if exists
        $floorplanDesign = $event->floorplanDesign;
        if ($floorplanDesign) {
            $floorplanDesign->load('items');

            // Load booking information for each item to determine availability
            $items = $floorplanDesign->items;
            foreach ($items as $item) {
                // Check if there's an active booking for this item
                $activeBooking = $item->bookings()
                    ->whereIn('status', ['reserved', 'booked'])
                    ->with('payments')
                    ->latest()
                    ->first();

                // Add booking status to item for frontend use
                if ($activeBooking) {
                    // Determine status based on booking and payment status
                    $item->booking_status = $activeBooking->status;


                    $item->booking_reference = $activeBooking->booking_reference;
                    $item->payment_status = $activeBooking->payment_status;
                    $item->total_paid = $activeBooking->total_paid;
                    $item->remaining_amount = $activeBooking->remaining_amount;

                    // Add owner details for company information display
                    $item->owner_details = $activeBooking->owner_details;

                    // Check if this is the user's current booking
                    if ($existingBooking->floorplan_item_id == $item->id) {
                        $item->is_current_booking = true;
                        $item->current_booking_status = $existingBooking->status;
                        $item->current_booking_progress = $this->getBookingProgress($existingBooking);
                    }
                } else {
                    $item->booking_status = 'available';
                    $item->booking_reference = null;
                    $item->payment_status = null;
                    $item->total_paid = 0;
                    $item->remaining_amount = 0;
                }
            }
        }

        // Set $booking for the layout to use
        $booking = $existingBooking;

        return view('bookings.floorplan', compact('event', 'floorplanDesign', 'existingBooking', 'accessToken', 'booking'));
    }

    /**
     * Get the current progress step for a booking
     */
    private function getBookingProgress(Booking $booking): int
    {
        // Step 1: Space selected (always true if booking exists)
        if (!$booking->owner_details || empty($booking->owner_details['name'])) {
            return 1;
        }

        // Step 2: Owner details completed
        if (!$booking->member_details || empty($booking->member_details)) {
            return 2;
        }

        // Step 3: Members added
        if (!$booking->payments || $booking->payments->isEmpty()) {
            return 3;
        }

        // Step 4: Payment completed
        return 4;
    }

    /**
     * Change the space for an existing booking (upgrade only)
     */
    public function changeSpace($eventSlug, $accessToken, $itemId)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $booking = $event->bookings()
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();

        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Access token has expired or is invalid.');
        }

        $newItem = FloorplanItem::findOrFail($itemId);
        $currentItem = $booking->floorplanItem;

        // Validate that this is an upgrade (new booth must be more expensive)
        if (!$currentItem) {
            return redirect()->route('events.public.floorplan-token', [
                'event' => $event->slug,
                'accessToken' => $accessToken
            ])->with('error', 'Current space information not found.');
        }

        $currentPrice = $currentItem->price ?? 0;
        $newPrice = $newItem->price ?? 0;

        // Only allow upgrades (new price must be higher)
        if ($newPrice <= $currentPrice) {
            return redirect()->route('events.public.floorplan-token', [
                'event' => $event->slug,
                'accessToken' => $accessToken
            ])->with('error', 'You can only change to a more expensive space. This space costs the same or less than your current space.');
        }

        // Check if the new item is available
        $existingBooking = Booking::where('floorplan_item_id', $itemId)
            ->where('event_id', $event->id)
            ->where('id', '!=', $booking->id) // Exclude current user's booking
            ->whereIn('status', ['reserved', 'booked'])
            ->first();

        if ($existingBooking) {
            return redirect()->route('events.public.floorplan-token', [
                'event' => $event->slug,
                'accessToken' => $accessToken
            ])->with('error', 'This space is already booked by another user.');
        }

        try {
            DB::beginTransaction();

            // Calculate price difference
            $priceDifference = $newPrice - $currentPrice;

            // Update the booking with the new floorplan item
            $booking->update([
                'floorplan_item_id' => $itemId,
                'total_amount' => $newPrice,
            ]);

            DB::commit();

            Log::info('Space upgraded successfully', [
                'booking_id' => $booking->id,
                'old_item_id' => $currentItem->id,
                'new_item_id' => $itemId,
                'old_price' => $currentPrice,
                'new_price' => $newPrice,
                'price_difference' => $priceDifference,
                'access_token' => $accessToken
            ]);

            $message = "Space upgraded successfully! New total: $" . number_format($newPrice, 2);
            if ($priceDifference > 0) {
                $message .= " (Additional amount due: $" . number_format($priceDifference, 2) . ")";
            }

            return redirect()->route('events.public.floorplan-token', [
                'event' => $event->slug,
                'accessToken' => $accessToken
            ])->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to change space', [
                'booking_id' => $booking->id,
                'new_item_id' => $itemId,
                'access_token' => $accessToken,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('events.public.floorplan-token', [
                'event' => $event->slug,
                'accessToken' => $accessToken
            ])->with('error', 'Failed to change space. Please try again.');
        }
    }

    /**
     * Resend payment confirmation email
     */
    public function resendPaymentEmail(Request $request, $eventSlug, $accessToken)
    {
        try {
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

            // Check if there's a completed payment
            $payment = $booking->payments()
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No completed payment found for this booking.'
                ], 400);
            }

            // Send payment confirmation email
            $emailService = app(EmailCommunicationService::class);
            $emailService->sendTriggeredEmail('payment_successful', $booking);

            Log::info('Payment confirmation email resent', [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'event_slug' => $eventSlug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmation email has been resent successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resend payment confirmation email', [
                'event_slug' => $eventSlug,
                'access_token' => $accessToken,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend email. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a specific booth member.
     */
    public function deleteMember(Request $request, $eventSlug, $accessToken, $memberId)
    {
        $request->validate([
            '_token' => 'required'
        ]);

        $event = Event::where('slug', $eventSlug)->firstOrFail();
        
        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'No booking found for this access token. Please start over.'
            ], 400);
        }

        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired access link. Please start over.'
            ], 400);
        }

        try {
            // Find the member to delete
            $member = \App\Models\BoothMember::where('id', $memberId)
                ->where('booth_owner_id', $boothOwner->id)
                ->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member not found or you do not have permission to delete them.'
                ], 404);
            }

            // Delete the member
            $member->delete();

            Log::info('Member deleted successfully', [
                'booking_id' => $booking->id,
                'member_id' => $memberId,
                'event_slug' => $eventSlug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Member deleted successfully!',
                'deleted_member_id' => $memberId
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete member', [
                'booking_id' => $booking->id,
                'member_id' => $memberId,
                'error' => $e->getMessage(),
                'event_slug' => $eventSlug
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete member. Please try again.'
            ], 500);
        }
    }
}
