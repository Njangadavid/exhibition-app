<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'status',
        'settings',
        'allow_multiple_submissions',
        'require_login',
        'send_confirmation_email',
        'confirmation_message',
        'redirect_url',
        'submit_button_text',
        'theme_color',
    ];

    protected $casts = [
        'settings' => 'array',
        'allow_multiple_submissions' => 'boolean',
        'require_login' => 'boolean',
        'send_confirmation_email' => 'boolean',
    ];

    /**
     * Get the event that owns the form builder
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the form fields for the form builder
     */
    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    /**
     * Get the form submissions for the form builder
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * Scope for published forms
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for draft forms
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get the form as JSON for frontend rendering
     */
    public function getFormJsonAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'settings' => $this->settings,
            'fields' => $this->fields->map(function ($field) {
                return [
                    'id' => $field->field_id,
                    'label' => $field->label,
                    'type' => $field->type,
                    'required' => $field->required,
                    'help_text' => $field->help_text,
                    'placeholder' => $field->placeholder,
                    'validation_rules' => $field->validation_rules,
                    'options' => $field->options,
                    'default_value' => $field->default_value,
                    'show_label' => $field->show_label,
                    'css_class' => $field->css_class,
                    'width' => $field->width,
                    'conditional_logic' => $field->conditional_logic,
                ];
            }),
        ];
    }
}
