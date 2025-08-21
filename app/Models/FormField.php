<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_builder_id',
        'field_id',
        'section_id',
        'label',
        'type',
        'sort_order',
        'required',
        'help_text',
        'placeholder',
        'validation_rules',
        'error_message',
        'options',
        'default_value',
        'default_option',
        'show_label',
        'css_class',
        'width',
        'conditional_logic',
    ];

    protected $casts = [
        'required' => 'boolean',
        'show_label' => 'boolean',
        'validation_rules' => 'array',
        'options' => 'array',
        'conditional_logic' => 'array',
    ];

    /**
     * Get the form builder that owns the field
     */
    public function formBuilder(): BelongsTo
    {
        return $this->belongsTo(FormBuilder::class);
    }

    /**
     * Get available field types
     */
    public static function getFieldTypes()
    {
        return [
            'text' => 'Text Input',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'textarea' => 'Text Area',
            'select' => 'Dropdown Select',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio Buttons',
            'file' => 'File Upload',
            'date' => 'Date Picker',
            'number' => 'Number Input',
            'url' => 'URL Input',
            'password' => 'Password',
            'hidden' => 'Hidden Field',
        ];
    }

    /**
     * Get available width options
     */
    public static function getWidthOptions()
    {
        return [
            'full' => 'Full Width (100%)',
            'half' => 'Half Width (50%)',
            'third' => 'One Third (33.33%)',
            'quarter' => 'One Quarter (25%)',
        ];
    }

    /**
     * Get validation rules for the field type
     */
    public function getDefaultValidationRules()
    {
        $rules = [];
        
        if ($this->required) {
            $rules[] = 'required';
        }

        switch ($this->type) {
            case 'email':
                $rules[] = 'email';
                break;
            case 'url':
                $rules[] = 'url';
                break;
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'file':
                $rules[] = 'file';
                break;
            case 'date':
                $rules[] = 'date';
                break;
        }

        return $rules;
    }

    /**
     * Get the field's HTML attributes
     */
    public function getHtmlAttributes()
    {
        $attributes = [
            'id' => $this->field_id,
            'name' => $this->field_id,
            'class' => 'form-control ' . ($this->css_class ?? ''),
        ];

        if ($this->required) {
            $attributes['required'] = 'required';
        }

        if ($this->placeholder) {
            $attributes['placeholder'] = $this->placeholder;
        }

        if ($this->default_value) {
            $attributes['value'] = $this->default_value;
        }

        return $attributes;
    }
}
