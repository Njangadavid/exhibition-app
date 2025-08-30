<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FloorplanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'floorplan_design_id',
        'item_id',
        'type',
        'x',
        'y',
        'width',
        'height',
        'radius',
        'size',
        'rotation',
        'fill_color',
        'stroke_color',
        'border_width',
        'font_family',
        'font_size',
        'text_color',
        'bookable',
        'max_capacity',
        'label',
        'item_name',
        'price',
        'label_position',
        'text_content',
        'label_font_size',
        'label_background_color',
        'label_color',
        'booth_width_meters',
        'booth_height_meters',
    ];

    protected $casts = [
        'x' => 'decimal:2',
        'y' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'radius' => 'decimal:2',
        'size' => 'decimal:2',
        'rotation' => 'decimal:2',
        'bookable' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the floorplan design that owns the item
     */
    public function floorplanDesign(): BelongsTo
    {
        return $this->belongsTo(FloorplanDesign::class);
    }

    /**
     * Get the bookings for this item
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'floorplan_item_id');
    }

    /**
     * Scope a query to only include bookable items
     */
    public function scopeBookable($query)
    {
        return $query->where('bookable', true);
    }

    /**
     * Scope a query to only include items of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the effective price (custom price or default from floorplan design)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->price ?? $this->floorplanDesign->default_price;
    }

    /**
     * Get the effective label position (custom or default from floorplan design)
     */
    public function getEffectiveLabelPositionAttribute()
    {
        return $this->label_position ?? $this->floorplanDesign->default_label_position;
    }

    /**
     * Get the effective label font size (custom or default from floorplan design)
     */
    public function getEffectiveLabelFontSizeAttribute()
    {
        return $this->label_font_size ?? $this->floorplanDesign->default_label_font_size;
    }

    /**
     * Get the effective label background color (custom or default from floorplan design)
     */
    public function getEffectiveLabelBackgroundColorAttribute()
    {
        return $this->label_background_color ?? $this->floorplanDesign->default_label_background_color;
    }

    /**
     * Get the effective label color (custom or default from floorplan design)
     */
    public function getEffectiveLabelColorAttribute()
    {
        return $this->label_color ?? $this->floorplanDesign->default_label_color;
    }

    /**
     * Get the effective stroke color (custom or default from floorplan design)
     */
    public function getEffectiveStrokeColorAttribute()
    {
        return $this->stroke_color ?? $this->floorplanDesign->stroke_color;
    }

    /**
     * Get the effective fill color (custom or default from floorplan design)
     */
    public function getEffectiveFillColorAttribute()
    {
        return $this->fill_color ?? $this->floorplanDesign->fill_color;
    }

    /**
     * Get the effective border width (custom or default from floorplan design)
     */
    public function getEffectiveBorderWidthAttribute()
    {
        return $this->border_width ?? $this->floorplanDesign->border_width;
    }

    /**
     * Get the effective font family (custom or default from floorplan design)
     */
    public function getEffectiveFontFamilyAttribute()
    {
        return $this->font_family ?? $this->floorplanDesign->font_family;
    }

    /**
     * Get the effective font size (custom or default from floorplan design)
     */
    public function getEffectiveFontSizeAttribute()
    {
        return $this->font_size ?? $this->floorplanDesign->font_size;
    }

    /**
     * Get the effective text color (custom or default from floorplan design)
     */
    public function getEffectiveTextColorAttribute()
    {
        return $this->text_color ?? $this->floorplanDesign->text_color;
    }

    /**
     * Get the effective booth width in meters (custom or default from floorplan design)
     */
    public function getEffectiveBoothWidthMetersAttribute()
    {
        return $this->booth_width_meters ?? $this->floorplanDesign->default_booth_width_meters ?? 3;
    }

    /**
     * Get the effective booth height in meters (custom or default from floorplan design)
     */
    public function getEffectiveBoothHeightMetersAttribute()
    {
        return $this->booth_height_meters ?? $this->floorplanDesign->default_booth_height_meters ?? 3;
    }
}