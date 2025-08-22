<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\FloorplanDesign;
use App\Models\FloorplanItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusOptions = Event::getStatusOptions();
        
        return view('events.create', compact('statusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,published,active,completed,cancelled',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Generate slug from title
        $validated['slug'] = Event::generateSlug($validated['title']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('events/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $statusOptions = Event::getStatusOptions();
        
        return view('events.edit', compact('event', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,published,active,completed,cancelled',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Generate slug from title (only if title changed)
        if ($event->title !== $validated['title']) {
            $validated['slug'] = Event::generateSlug($validated['title']);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($event->logo) {
                Storage::disk('public')->delete($event->logo);
            }
            
            $logoPath = $request->file('logo')->store('events/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Delete logo if exists
        if ($event->logo) {
            Storage::disk('public')->delete($event->logo);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Public event display (no authentication required).
     */
    public function publicShow(Event $event)
    {
        // Check if event is published or active
        if (!in_array($event->status, ['published', 'active'])) {
            abort(404, 'Event not found.');
        }

        return view('events.public.show', compact('event'));
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
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->with('payments')
                    ->latest()
                    ->first();
                
                // Add booking status to item for frontend use
                if ($activeBooking) {
                    // Determine status based on booking and payment status
                    if ($activeBooking->status === 'confirmed') {
                        $item->booking_status = 'confirmed';
                    } elseif ($activeBooking->hasPayments()) {
                        $item->booking_status = 'pending';
                    } else {
                        $item->booking_status = 'pending';
                    }
                    
                    $item->booking_reference = $activeBooking->booking_reference;
                    $item->payment_status = $activeBooking->payment_status;
                    $item->total_paid = $activeBooking->total_paid;
                    $item->remaining_amount = $activeBooking->remaining_amount;
                    
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
        
        return view('events.public.floorplan', compact('event', 'floorplanDesign', 'existingBooking', 'accessToken'));
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
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->with('payments')
                    ->latest()
                    ->first();
                
                // Add booking status to item for frontend use
                if ($activeBooking) {
                    // Determine status based on booking and payment status
                    if ($activeBooking->status === 'confirmed') {
                        $item->booking_status = 'confirmed';
                    } elseif ($activeBooking->hasPayments()) {
                        $item->booking_status = 'pending';
                    } else {
                        $item->booking_status = 'pending';
                    }
                    
                    $item->booking_reference = $activeBooking->booking_reference;
                    $item->payment_status = $activeBooking->payment_status;
                    $item->total_paid = $activeBooking->total_paid;
                    $item->remaining_amount = $activeBooking->remaining_amount;
                    
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
        
        return view('events.public.floorplan', compact('event', 'floorplanDesign', 'existingBooking', 'accessToken'));
    }

    /**
     * Get the current progress step for a booking
     */
    private function getBookingProgress($booking)
    {
        if ($booking->status === 'confirmed') {
            return 4; // Payment completed
        } elseif ($booking->member_details && count($booking->member_details) > 0) {
            return 3; // Members added
        } elseif ($booking->owner_details) {
            return 2; // Owner details submitted
        } else {
            return 1; // Just started
        }
    }

    /**
     * Dashboard view for events.
     */
    public function dashboard()
    {
        $activeEvents = Event::active()->count();
        $upcomingEvents = Event::upcoming()->count();
        $completedEvents = Event::completed()->count();
        $totalEvents = Event::count();
        
        $recentEvents = Event::latest()->take(5)->get();
        $upcomingEventsList = Event::upcoming()->take(3)->get();

        return view('events.dashboard', compact(
            'activeEvents',
            'upcomingEvents', 
            'completedEvents',
            'totalEvents',
            'recentEvents',
            'upcomingEventsList'
        ));
    }

    /**
     * Event-specific dashboard.
     */
    public function eventDashboard(Event $event)
    {
        return view('events.dashboard', compact('event'));
    }

    /**
     * Event floorplan.
     */
    public function floorplan(Event $event)
    {
        // Load existing floorplan design if exists
        $floorplanDesign = $event->floorplanDesign()->with('items')->first();
        
        return view('events.floorplan', compact('event', 'floorplanDesign'));
    }

    /**
     * Event registration form.
     */
    public function registration(Event $event)
    {
        return view('events.registration', compact('event'));
    }

    /**
     * Save floorplan design and items.
     */
    public function saveFloorplan(Request $request, Event $event)
    {
        $validated = $request->validate([
            // Floorplan design properties
            'name' => 'string|max:255',
            'canvas_size' => 'string',
            'canvas_width' => 'integer',
            'canvas_height' => 'integer',
            'bg_color' => 'string',
            'fill_color' => 'string',
            'stroke_color' => 'string',
            'text_color' => 'string',
            'border_width' => 'integer',
            'font_family' => 'string',
            'font_size' => 'integer',
            'grid_size' => 'integer',
            'grid_color' => 'string',
            'show_grid' => 'boolean',
            'snap_to_grid' => 'boolean',
            'default_booth_capacity' => 'integer',
            'label_prefix' => 'string|max:3',
            'starting_label_number' => 'integer',
            'default_label_position' => 'string|in:top,bottom,left,right,center',
            'default_price' => 'numeric',
            'enable_auto_labeling' => 'boolean',
            
            // Items array
            'items' => 'array',
            'items.*.item_id' => 'required|string',
            'items.*.type' => 'required|string',
            'items.*.x' => 'required|numeric',
            'items.*.y' => 'required|numeric',
            'items.*.width' => 'nullable|numeric',
            'items.*.height' => 'nullable|numeric',
            'items.*.radius' => 'nullable|numeric',
            'items.*.size' => 'nullable|numeric',
            'items.*.rotation' => 'numeric',
            'items.*.fill_color' => 'string',
            'items.*.stroke_color' => 'string',
            'items.*.border_width' => 'integer',
            'items.*.bookable' => 'boolean',
            'items.*.max_capacity' => 'integer',
            'items.*.label' => 'nullable|string',
            'items.*.item_name' => 'nullable|string',
            'items.*.price' => 'nullable|numeric',
            'items.*.label_position' => 'nullable|string|in:top,bottom,left,right,center',
            'items.*.text_content' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create or update floorplan design
            $floorplanDesign = $event->floorplanDesign()->updateOrCreate(
                ['event_id' => $event->id],
                array_filter($validated, function($key) {
                    return $key !== 'items';
                }, ARRAY_FILTER_USE_KEY)
            );

            // Delete existing items and create new ones
            $floorplanDesign->items()->delete();

            if (isset($validated['items'])) {
                foreach ($validated['items'] as $itemData) {
                    $itemData['floorplan_design_id'] = $floorplanDesign->id;
                    FloorplanItem::create($itemData);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Floorplan saved successfully!',
                'floorplan_id' => $floorplanDesign->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving floorplan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Load floorplan design and items.
     */
    public function loadFloorplan(Event $event)
    {
        $floorplanDesign = $event->floorplanDesign()->with('items')->first();

        if (!$floorplanDesign) {
            return response()->json([
                'success' => false,
                'message' => 'No floorplan found for this event'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'floorplan' => $floorplanDesign->toArray()
        ]);
    }
}
