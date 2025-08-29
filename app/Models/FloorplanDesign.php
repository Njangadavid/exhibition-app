<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FloorplanDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'canvas_size',
        'canvas_width',
        'canvas_height',
        'bg_color',
        'fill_color',
        'stroke_color',
        'text_color',
        'border_width',
        'font_family',
        'font_size',
        'grid_size',
        'grid_color',
        'show_grid',
        'snap_to_grid',
        'default_booth_capacity',
        'label_prefix',
        'starting_label_number',
        'default_label_position',
        'default_price',
        'enable_auto_labeling',
        'default_booth_width_meters',
        'default_booth_height_meters',
        'default_label_font_size',
        'default_label_background_color',
        'default_label_color',

    ];

    protected $casts = [
        'show_grid' => 'boolean',
        'snap_to_grid' => 'boolean',
        'enable_auto_labeling' => 'boolean',
        'default_price' => 'decimal:2',
        'default_booth_width_meters' => 'decimal:2',
        'default_booth_height_meters' => 'decimal:2',
        'default_label_font_size' => 'integer',
    ];

    /**
     * Get the event that owns the floorplan design
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the items for the floorplan design
     */
    public function items(): HasMany
    {
        return $this->hasMany(FloorplanItem::class);
    }

    /**
     * Get the bookable items for the floorplan design
     */
    public function bookableItems(): HasMany
    {
        return $this->hasMany(FloorplanItem::class)->where('bookable', true);
    }
}