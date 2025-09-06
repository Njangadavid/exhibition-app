<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'logo',
        'start_date',
        'end_date',
        'owner_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the status options for the event.
     */
    public static function getStatusOptions()
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
            'active' => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'published' => 'blue',
            'active' => 'green',
            'completed' => 'purple',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Check if the event is currently active.
     */
    public function isActive()
    {
        $now = Carbon::now();
        return $this->status === 'active' && 
               $this->start_date <= $now && 
               $this->end_date >= $now;
    }

    /**
     * Check if the event is upcoming.
     */
    public function isUpcoming()
    {
        return $this->start_date > Carbon::now();
    }

    /**
     * Check if the event is completed.
     */
    public function isCompleted()
    {
        return $this->end_date < Carbon::now();
    }

    /**
     * Get the duration of the event in days.
     */
    public function getDurationInDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Generate a unique slug for the event.
     */
    public static function generateSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key name for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope for active events.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    /**
     * Scope for completed events.
     */
    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', Carbon::now());
    }

    /**
     * Get the floorplan design for the event
     */
    public function floorplanDesign(): HasOne
    {
        return $this->hasOne(FloorplanDesign::class);
    }

    /**
     * Get the form builders for the event
     */
    public function formBuilders(): HasMany
    {
        return $this->hasMany(FormBuilder::class);
    }

    /**
     * Get the payment methods for the event
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get the bookings for the event
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }



    /**
     * Get the email templates for the event
     */
    public function emailTemplates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class);
    }

    /**
     * Get the email settings for the event
     */
    public function emailSettings(): HasOne
    {
        return $this->hasOne(EventEmailSettings::class);
    }

    /**
     * Get the count of booked booths for this event
     */
    public function getBookedBoothsCountAttribute(): int
    {
        if (!$this->floorplanDesign) {
            return 0;
        }

        $bookableItemIds = $this->floorplanDesign->items()
            ->where('bookable', true)
            ->pluck('id');

        return $this->bookings()
            ->whereIn('floorplan_item_id', $bookableItemIds)
            ->where('status', 'booked')
            ->count();
    }

    /**
     * Get the count of reserved booths for this event
     */
    public function getReservedBoothsCountAttribute(): int
    {
        if (!$this->floorplanDesign) {
            return 0;
        }

        $bookableItemIds = $this->floorplanDesign->items()
            ->where('bookable', true)
            ->pluck('id');

        return $this->bookings()
            ->whereIn('floorplan_item_id', $bookableItemIds)
            ->where('status', 'reserved')
            ->count();
    }

    /**
     * Get the count of available booths for this event
     */
    public function getAvailableBoothsCountAttribute(): int
    {
        if (!$this->floorplanDesign) {
            return 0;
        }

        $totalBookableItems = $this->floorplanDesign->items()
            ->where('bookable', true)
            ->count();

        $bookedOrReservedCount = $this->bookings()
            ->whereIn('status', ['booked', 'reserved'])
            ->whereHas('floorplanItem', function($query) {
                $query->where('bookable', true);
            })
            ->count();

        return max(0, $totalBookableItems - $bookedOrReservedCount);
    }

    /**
     * Get the total revenue collected for this event
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->bookings()
            ->with('payments')
            ->get()
            ->sum(function($booking) {
                return $booking->payments()
                    ->where('status', 'completed')
                    ->sum('amount');
            });
    }

    /**
     * Get the payment rate percentage for this event
     */
    public function getPaymentRateAttribute(): int
    {
        $totalBookings = $this->bookings()->count();
        if ($totalBookings === 0) {
            return 0;
        }

        $paidBookings = $this->bookings()
            ->whereHas('payments', function($query) {
                $query->where('status', 'completed');
            })
            ->count();

        return round(($paidBookings / $totalBookings) * 100);
    }

    /**
     * Get the owner of this event
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users assigned to this event
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    /**
     * Check if a user can view this event
     */
    public function canBeViewedBy(User $user): bool
    {
        // Administrators can view all events
        if ($user->hasRole('admin')) {
            return true;
        }

        // Event owner can always view their event
        if ($this->owner_id === $user->id) {
            return true;
        }

        // Check if user is assigned to this event
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the owner of this event
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Get all users that should be assigned to this event (including owner)
     */
    public function getAllAssignedUsers()
    {
        $assignedUsers = $this->users;
        
        // Always include the owner if they exist
        if ($this->owner && !$assignedUsers->contains('id', $this->owner->id)) {
            $assignedUsers->push($this->owner);
        }
        
        return $assignedUsers;
    }

    /**
     * Check if a user can manage event ownership (only admins)
     */
    public function canManageOwnership(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Transfer ownership to another user (admin only)
     */
    public function transferOwnership(User $newOwner, User $admin): bool
    {
        if (!$this->canManageOwnership($admin)) {
            return false;
        }

        $this->update(['owner_id' => $newOwner->id]);
        
        // Ensure new owner is assigned to the event
        $this->users()->syncWithoutDetaching([$newOwner->id]);
        
        return true;
    }

    /**
     * Scope to get events visible to a specific user
     */
    public function scopeVisibleTo($query, User $user)
    {
        // Administrators can see all events
        if ($user->hasRole('admin')) {
            return $query;
        }

        // Users can see events they own or are assigned to
        return $query->where(function ($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhereHas('users', function ($subQ) use ($user) {
                  $subQ->where('user_id', $user->id);
              });
        });
    }
}
