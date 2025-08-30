<?php

namespace App\Helpers;

use Illuminate\Support\Collection;


class BoothMemberHelper
{
    /**
     * Get a field value from booth member form_responses by field_purpose
     *
     * @param array $memberData The booth member data containing form_responses and form_fields
     * @param string $fieldPurpose The field_purpose to search for (e.g., 'member_name', 'member_email')
     * @param mixed $defaultValue Default value if field not found
     * @return mixed The field value or default value
     */
    public static function getFieldValueByPurpose($boothMember, string $fieldPurpose, $defaultValue = null)
    {
        try {
            // Check if boothOwner relationship exists
            if (!$boothMember->boothOwner) {
                return $defaultValue;
            }

            // Check if booking relationship exists
            if (!$boothMember->boothOwner->booking) {
                return $defaultValue;
            }

            // Check if event relationship exists
            if (!$boothMember->boothOwner->booking->event) {
                return $defaultValue;
            }

            // Get member registration form builder
            $formBuilder = $boothMember->boothOwner->booking->event->formBuilders()
                ->where('type', 'member_registration')
                ->first();
            
            if (!$formBuilder) {
                return $defaultValue;
            }

            // Find field by field_purpose
            $formField = $formBuilder->fields()->where('field_purpose', $fieldPurpose)->first();
            
            if (!$formField) {
                return $defaultValue;
            }

            // Get the field_id to use as key
            $fieldId = $formField->field_id;
            
            // Check if form_responses exists and has the field
            if (!is_array($boothMember->form_responses) || !array_key_exists($fieldId, $boothMember->form_responses)) {
                return $defaultValue;
            }

            return $boothMember->form_responses[$fieldId];

        } catch (\Exception $e) {
            return $defaultValue;
        }
    }

    /**
     * Get multiple field values by their purposes
     *
     * @param array $memberData The booth member data
     * @param array $fieldPurposes Array of field purposes to extract
     * @param array $defaultValues Associative array of default values
     * @return array Associative array of field values
     */
    public static function getMultipleFieldValues(array $memberData, array $fieldPurposes, array $defaultValues = []): array
    {
        $result = [];
        
        foreach ($fieldPurposes as $purpose) {
            $default = $defaultValues[$purpose] ?? null;
            $result[$purpose] = self::getFieldValueByPurpose($memberData, $purpose, $default);
        }
        
        return $result;
    }

    /**
     * Get all available field purposes from a member's form_fields
     *
     * @param array $memberData The booth member data
     * @return array Array of field purposes
     */
    public static function getAvailableFieldPurposes(array $memberData): array
    {
        if (!isset($memberData['form_fields'])) {
            return [];
        }

        $purposes = [];
        foreach ($memberData['form_fields'] as $field) {
            if (isset($field['field_purpose']) && !empty($field['field_purpose'])) {
                $purposes[] = $field['field_purpose'];
            }
        }

        return array_unique($purposes);
    }

    /**
     * Get a field label by field_purpose
     *
     * @param array $memberData The booth member data
     * @param string $fieldPurpose The field_purpose to search for
     * @param string $defaultLabel Default label if field not found
     * @return string The field label or default label
     */
    public static function getFieldLabelByPurpose(array $memberData, string $fieldPurpose, string $defaultLabel = 'Unknown Field'): string
    {
        if (!isset($memberData['form_fields'])) {
            return $defaultLabel;
        }

        foreach ($memberData['form_fields'] as $field) {
            if (isset($field['field_purpose']) && $field['field_purpose'] === $fieldPurpose) {
                return $field['label'] ?? $defaultLabel;
            }
        }

        return $defaultLabel;
    }

    /**
     * Check if a field with specific purpose exists
     *
     * @param array $memberData The booth member data
     * @param string $fieldPurpose The field_purpose to check
     * @return bool True if field exists, false otherwise
     */
    public static function hasFieldPurpose(array $memberData, string $fieldPurpose): bool
    {
        if (!isset($memberData['form_fields'])) {
            return false;
        }

        foreach ($memberData['form_fields'] as $field) {
            if (isset($field['field_purpose']) && $field['field_purpose'] === $fieldPurpose) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find value by intelligent pattern matching when field_purpose is not available
     *
     * @param array $formResponses The form_responses array
     * @param string $fieldPurpose The field_purpose we're looking for
     * @param mixed $defaultValue Default value if not found
     * @return mixed The found value or default
     */
    private static function findValueByPattern(array $formResponses, string $fieldPurpose, $defaultValue = null)
    {
        switch ($fieldPurpose) {
            case 'member_name':
                // Look for fields that might contain names (no @ symbol, reasonable length)
                foreach ($formResponses as $fieldId => $value) {
                    if (is_string($value) && 
                        strlen($value) > 0 && 
                        strlen($value) < 100 && 
                        !str_contains($value, '@') && 
                        !str_contains($value, 'http') &&
                        !is_numeric($value)) {
                        return $value;
                    }
                }
                break;

            case 'member_email':
                // Look for fields that look like email addresses
                foreach ($formResponses as $fieldId => $value) {
                    if (is_string($value) && 
                        str_contains($value, '@') && 
                        str_contains($value, '.') &&
                        filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $value;
                    }
                }
                break;

            case 'member_phone':
                // Look for fields that might contain phone numbers
                foreach ($formResponses as $fieldId => $value) {
                    if (is_string($value) && 
                        (preg_match('/^[\d\s\-\+\(\)]+$/', $value) || 
                         preg_match('/^\+?[\d\s\-\(\)]{7,}$/', $value))) {
                        return $value;
                    }
                }
                break;

            case 'member_company':
                // Look for fields that might contain company names
                foreach ($formResponses as $fieldId => $value) {
                    if (is_string($value) && 
                        strlen($value) > 2 && 
                        strlen($value) < 100 && 
                        !str_contains($value, '@') && 
                        !str_contains($value, 'http') &&
                        !is_numeric($value)) {
                        return $value;
                    }
                }
                break;

            case 'member_title':
                // Look for fields that might contain job titles
                foreach ($formResponses as $fieldId => $value) {
                    if (is_string($value) && 
                        strlen($value) > 2 && 
                        strlen($value) < 50 && 
                        !str_contains($value, '@') && 
                        !str_contains($value, 'http') &&
                        !is_numeric($value)) {
                        return $value;
                    }
                }
                break;
        }

        return $defaultValue;
    }

    /**
     * Get a summary of all available data for a member
     *
     * @param array $memberData The booth member data
     * @return array Summary of member data
     */
    public static function getMemberSummary(array $memberData): array
    {
        $summary = [
            'name' => self::getFieldValueByPurpose($memberData, 'member_name', 'Unknown Member'),
            'email' => self::getFieldValueByPurpose($memberData, 'member_email', 'No email'),
            'phone' => self::getFieldValueByPurpose($memberData, 'member_phone'),
            'company' => self::getFieldValueByPurpose($memberData, 'member_company'),
            'title' => self::getFieldValueByPurpose($memberData, 'member_title'),
            'available_purposes' => self::getAvailableFieldPurposes($memberData),
            'total_fields' => isset($memberData['form_responses']) ? count($memberData['form_responses']) : 0
        ];

        return $summary;
    }


}
