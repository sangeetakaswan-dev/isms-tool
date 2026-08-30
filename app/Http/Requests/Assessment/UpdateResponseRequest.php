<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:compliant,non_compliant,partially_compliant,not_applicable,not_assessed'],
            'maturity_level' => ['nullable', 'integer', 'min:0', 'max:5'],
            'evidence_notes' => ['nullable', 'string', 'max:10000'],
            'gap_description' => ['nullable', 'string', 'max:10000'],
            'not_applicable_reason' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Assessment status is required.',
            'status.in' => 'Invalid assessment status provided.',
            'maturity_level.integer' => 'Maturity level must be a number.',
            'maturity_level.min' => 'Maturity level must be between 0 and 5.',
            'maturity_level.max' => 'Maturity level must be between 0 and 5.',
        ];
    }
}