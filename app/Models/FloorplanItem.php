<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}