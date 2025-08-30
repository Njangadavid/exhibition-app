<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoothMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add proper authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'form_responses' => 'required|array',
            'form_responses.*' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'form_responses.required' => 'Form responses are required.',
            'form_responses.array' => 'Form responses must be an array.',
            'form_responses.*.string' => 'Each form response must be a string.',
            'form_responses.*.max' => 'Each form response must not exceed 1000 characters.',
        ];
    }
}
