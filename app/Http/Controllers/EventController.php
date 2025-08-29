<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;

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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:' . implode(',', array_keys(Event::getStatusOptions())),
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Generate slug from name
        $validated['slug'] = Event::generateSlug($validated['name']);

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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:' . implode(',', array_keys(Event::getStatusOptions())),
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Generate slug from name (only if name changed)
        if ($event->name !== $validated['name']) {
            $validated['slug'] = Event::generateSlug($validated['name']);
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
        try {
            // Log the incoming request data
            \Illuminate\Support\Facades\Log::info('Floorplan save request received', [
                'event_id' => $event->id,
                'request_data' => $request->all(),
                'items_count' => count($request->input('items', [])),
                'sample_item' => $request->input('items.0', 'No items')
            ]);
            
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
            'default_booth_width_meters' => 'numeric|min:0.1|max:100',
            'default_booth_height_meters' => 'numeric|min:0.1|max:100',
            'default_label_font_size' => 'integer|min:8|max:72',
            'default_label_background_color' => 'string|regex:/^#[0-9A-F]{6}$/i',
            'default_label_color' => 'string|regex:/^#[0-9A-F]{6}$/i',

            
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
            'items.*.rotation' => 'nullable|numeric',
            'items.*.fill_color' => 'nullable|string',
            'items.*.stroke_color' => 'nullable|string',
            'items.*.border_width' => 'nullable|integer',
            'items.*.font_family' => 'nullable|string',
            'items.*.font_size' => 'nullable|integer',
            'items.*.text_color' => 'nullable|string',
            'items.*.bookable' => 'boolean',
            'items.*.max_capacity' => 'nullable|integer',
            'items.*.label' => 'nullable|string',
            'items.*.item_name' => 'nullable|string',
            'items.*.price' => 'nullable|numeric',
            'items.*.label_position' => 'nullable|string|in:top,bottom,left,right,center',
            'items.*.text_content' => 'nullable|string',
            'items.*.label_font_size' => 'nullable|integer|min:8|max:72',
            'items.*.label_background_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'items.*.label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'items.*.booth_width_meters' => 'nullable|numeric|min:0.1|max:100',
            'items.*.booth_height_meters' => 'nullable|numeric|min:0.1|max:100',
        ]);
        
        } catch (ValidationException $e) {
            \Illuminate\Support\Facades\Log::warning('Floorplan validation failed', [
                'event_id' => $event->id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Floorplan validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            \Illuminate\Support\Facades\Log::info('Database transaction started for floorplan save');

            // Create or update floorplan design
            $floorplanDesign = $event->floorplanDesign()->updateOrCreate(
                ['event_id' => $event->id],
                array_filter($validated, function($key) {
                    return $key !== 'items';
                }, ARRAY_FILTER_USE_KEY)
            );

            \Illuminate\Support\Facades\Log::info('Floorplan design created/updated', [
                'floorplan_id' => $floorplanDesign->id,
                'event_id' => $event->id
            ]);

            if (isset($validated['items'])) {
                // Get existing items to preserve IDs
                $existingItems = $floorplanDesign->items()->get()->keyBy('item_id');
                $newItemIds = collect($validated['items'])->pluck('item_id')->toArray();
                
                \Illuminate\Support\Facades\Log::info('Processing floorplan items', [
                    'existing_count' => $existingItems->count(),
                    'new_count' => count($validated['items']),
                    'sample_item_data' => $validated['items'][0] ?? 'No items'
                ]);
                
                // Delete items that are no longer in the floorplan
                $itemsToDelete = $existingItems->keys()->diff($newItemIds);
                if ($itemsToDelete->count() > 0) {
                    \Illuminate\Support\Facades\Log::info('Items to be deleted', [
                        'item_ids_to_delete' => $itemsToDelete->toArray(),
                        'floorplan_id' => $floorplanDesign->id
                    ]);
                    
                    // Check if any of these items have bookings
                    $itemsWithBookings = $floorplanDesign->items()
                        ->whereIn('item_id', $itemsToDelete)
                        ->whereHas('bookings')
                        ->get();
                    
                    if ($itemsWithBookings->count() > 0) {
                        \Illuminate\Support\Facades\Log::warning('Cannot delete items with existing bookings', [
                            'items_with_bookings' => $itemsWithBookings->pluck('item_name', 'item_id')
                        ]);
                        
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot delete floorplan items that have existing bookings. Please remove all bookings for these items first.',
                            'items_with_bookings' => $itemsWithBookings->pluck('item_name', 'item_id')
                        ], 422);
                    }
                    
                    $floorplanDesign->items()->whereIn('item_id', $itemsToDelete)->delete();
                    \Illuminate\Support\Facades\Log::info('Deleted floorplan items', ['deleted_count' => $itemsToDelete->count()]);
                }
                
                // Update or create items
                foreach ($validated['items'] as $index => $itemData) {
                    \Illuminate\Support\Facades\Log::info('Processing item', [
                        'index' => $index,
                        'item_id' => $itemData['item_id'],
                        'type' => $itemData['type'],
                        'fill_color' => $itemData['fill_color'],
                        'stroke_color' => $itemData['stroke_color'],
                        'border_width' => $itemData['border_width'],
                        'label_font_size' => $itemData['label_font_size'] ?? 'null',
                        'label_background_color' => $itemData['label_background_color'] ?? 'null',
                        'label_color' => $itemData['label_color'] ?? 'null'
                    ]);
                    
                    $itemData['floorplan_design_id'] = $floorplanDesign->id;
                    
                    if ($existingItems->has($itemData['item_id'])) {
                        // Update existing item to preserve ID and relationships
                        $existingItem = $existingItems->get($itemData['item_id']);
                        $existingItem->update($itemData);
                        \Illuminate\Support\Facades\Log::info('Updated existing item', [
                            'item_id' => $itemData['item_id'],
                            'label_font_size' => $itemData['label_font_size'] ?? 'null',
                            'label_background_color' => $itemData['label_background_color'] ?? 'null',
                            'label_color' => $itemData['label_color'] ?? 'null'
                        ]);
                    } else {
                        // Create new item
                        FloorplanItem::create($itemData);
                        \Illuminate\Support\Facades\Log::info('Created new item', [
                            'item_id' => $itemData['item_id'],
                            'label_font_size' => $itemData['label_font_size'] ?? 'null',
                            'label_background_color' => $itemData['label_background_color'] ?? 'null',
                            'label_color' => $itemData['label_color'] ?? 'null'
                        ]);
                    }
                }
                
                \Illuminate\Support\Facades\Log::info('Floorplan items processed successfully');
            }

            DB::commit();
            \Illuminate\Support\Facades\Log::info('Floorplan saved successfully', [
                'floorplan_id' => $floorplanDesign->id,
                'total_items' => isset($validated['items']) ? count($validated['items']) : 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Floorplan saved successfully!',
                'floorplan_id' => $floorplanDesign->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Illuminate\Support\Facades\Log::error('Error saving floorplan', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
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

    /**
     * Check for orphaned bookings (bookings that reference non-existent floorplan items)
     */
    public function checkOrphanedBookings(Event $event)
    {
        try {
            // First, let's check if the event has any bookings at all
            $totalBookings = $event->bookings()->count();
            
            // Get all bookings for this event
            $allBookings = $event->bookings()->get();
            
            // Check which bookings have valid floorplan items
            $orphanedBookings = collect();
            
            foreach ($allBookings as $booking) {
                // Check if the floorplan item exists
                $floorplanItem = \App\Models\FloorplanItem::find($booking->floorplan_item_id);
                if (!$floorplanItem) {
                    $orphanedBookings->push($booking);
                }
            }
            
            return response()->json([
                'success' => true,
                'orphaned_bookings' => $orphanedBookings->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'status' => $booking->status,
                        'owner_name' => $booking->owner_details['name'] ?? 'Unknown',
                        'owner_email' => $booking->owner_details['email'] ?? 'Unknown',
                        'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                        'floorplan_item_id' => $booking->floorplan_item_id,
                    ];
                }),
                'count' => $orphanedBookings->count(),
                'total_bookings' => $totalBookings,
                'debug_info' => [
                    'event_id' => $event->id,
                    'event_name' => $event->name,
                    'method' => 'manual_check'
                ]
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error checking orphaned bookings', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking orphaned bookings: ' . $e->getMessage(),
                'debug_info' => [
                    'event_id' => $event->id,
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Clean up orphaned bookings (remove bookings that reference non-existent floorplan items)
     */
    public function cleanupOrphanedBookings(Event $event)
    {
        try {
            DB::beginTransaction();

            // Get all bookings for this event
            $allBookings = $event->bookings()->get();
            
            // Check which bookings have valid floorplan items
            $orphanedBookings = collect();
            
            foreach ($allBookings as $booking) {
                // Check if the floorplan item exists
                $floorplanItem = \App\Models\FloorplanItem::find($booking->floorplan_item_id);
                if (!$floorplanItem) {
                    $orphanedBookings->push($booking);
                }
            }

            $count = $orphanedBookings->count();
            
            if ($count > 0) {
                $orphanedBookings->each(function($booking) {
                    // Log the cleanup for audit purposes
                    \Illuminate\Support\Facades\Log::warning('Cleaning up orphaned booking', [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'event_id' => $booking->event_id,
                        'floorplan_item_id' => $booking->floorplan_item_id,
                        'owner_email' => $booking->owner_details['email'] ?? 'Unknown'
                    ]);
                    
                    $booking->delete();
                });
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Cleaned up {$count} orphaned bookings",
                'cleaned_count' => $count,
                'total_bookings_checked' => $allBookings->count()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Illuminate\Support\Facades\Log::error('Error cleaning up orphaned bookings', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error cleaning up orphaned bookings: ' . $e->getMessage(),
                'debug_info' => [
                    'event_id' => $event->id,
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ]
            ], 500);
        }
    }
}
