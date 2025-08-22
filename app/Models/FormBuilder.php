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
        'type',
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
        $allFields = $this->fields;
        
        // Separate sections and regular fields
        $sections = $allFields->where('type', 'section')->map(function ($section) {
            return [
                'id' => $section->field_id,
                'name' => $section->label,
                'label' => $section->label,
            ];
        })->values();
        
        $fields = $allFields->where('type', '!=', 'section')->map(function ($field) {
            return [
                'id' => $field->field_id,
                'label' => $field->label,
                'type' => $field->type,
                'field_purpose' => $field->field_purpose ?? 'general',
                'required' => $field->required,
                'help_text' => $field->help_text,
                'placeholder' => $field->placeholder,
                'validation_rules' => $field->validation_rules,
                'options' => $field->options,
                'default_value' => $field->default_value,
                'default_option' => $field->default_option,
                'show_label' => $field->show_label,
                'css_class' => $field->css_class,
                'width' => $field->width,
                'col_size' => $this->getColSizeFromWidth($field->width),
                'section_id' => $field->section_id,
                'conditional_logic' => $field->conditional_logic,
            ];
        })->values();
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'settings' => $this->settings,
            'sections' => $sections,
            'fields' => $fields,
        ];
    }
    
    /**
     * Convert width to Bootstrap col-size
     */
    private function getColSizeFromWidth($width)
    {
        return match($width) {
            'quarter' => 3,
            'third' => 4,
            'half' => 6,
            'full' => 12,
            default => 12,
        };
    }

    /**
     * Scope for member registration forms
     */
    public function scopeMemberRegistration($query)
    {
        return $query->where('type', 'member_registration');
    }

    /**
     * Scope for exhibitor registration forms
     */
    public function scopeExhibitorRegistration($query)
    {
        return $query->where('type', 'exhibitor_registration');
    }

    /**
     * Check if this is a member registration form
     */
    public function isMemberRegistration(): bool
    {
        return $this->type === 'member_registration';
    }

    /**
     * Check if this is an exhibitor registration form
     */
    public function isExhibitorRegistration(): bool
    {
        return $this->type === 'exhibitor_registration';
    }

    /**
     * Get the form type display name
     */
    public function getTypeDisplayNameAttribute(): string
    {
        return match($this->type) {
            'member_registration' => 'Member Registration',
            'exhibitor_registration' => 'Exhibitor Registration',
            'speaker_registration' => 'Speaker Registration',
            'delegate_registration' => 'Delegate Registration',
            'general' => 'General Form',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}
