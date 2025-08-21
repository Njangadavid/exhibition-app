<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_builder_id',
        'user_id',
        'submission_id',
        'form_data',
        'status',
        'ip_address',
        'user_agent',
        'referrer',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'form_data' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the form builder that owns the submission
     */
    public function formBuilder(): BelongsTo
    {
        return $this->belongsTo(FormBuilder::class);
    }

    /**
     * Get the user who made the submission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who reviewed the submission
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope for pending submissions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope for reviewed submissions
     */
    public function scopeReviewed($query)
    {
        return $query->whereIn('status', ['approved', 'rejected']);
    }

    /**
     * Get a specific field value from form data
     */
    public function getFieldValue($fieldId)
    {
        return $this->form_data[$fieldId] ?? null;
    }

    /**
     * Get all field values as a formatted array
     */
    public function getFormattedData()
    {
        $formatted = [];
        $fields = $this->formBuilder->fields;

        foreach ($fields as $field) {
            $value = $this->getFieldValue($field->field_id);
            $formatted[$field->label] = $value;
        }

        return $formatted;
    }
}
