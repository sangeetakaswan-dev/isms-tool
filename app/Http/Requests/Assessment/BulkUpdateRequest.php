<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        return [
            'control_ids' => ['required', 'array', 'min:1'],
            'control_ids.*' => ['required', 'integer', 'exists:controls,id'],
            'status' => ['required', 'string', 'in:compliant,non_compliant,partially_compliant,not_assessed'],
        ];
    }

    public function messages(): array
    {
        return [
            'control_ids.required' => 'At least one control must be selected.',
            'control_ids.min' => 'At least one control must be selected.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status provided.',
        ];
    }
}