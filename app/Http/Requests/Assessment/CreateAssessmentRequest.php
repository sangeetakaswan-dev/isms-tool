<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class CreateAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'scope' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date'],
            'target_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'lead_assessor_id' => ['required', 'integer', 'exists:users,id'],
            'team_members' => ['nullable', 'array'],
            'team_members.*' => ['string', 'in:lead_assessor,assessor,reviewer'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Assessment name is required.',
            'start_date.required' => 'Start date is required.',
            'lead_assessor_id.required' => 'Lead assessor is required.',
            'target_date.after_or_equal' => 'Target date must be after or equal to start date.',
        ];
    }
}