<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'logo',
        'start_date',
        'end_date',
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
    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
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
}
