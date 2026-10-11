<?php

namespace App\Http\Requests\Risk;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'strategy'      => ['required', 'in:mitigate,accept,transfer,avoid'],
            'action'        => ['required', 'string', 'max:2000'],
            'cost_estimate' => ['nullable', 'numeric', 'min:0'],
            'due_date'      => ['nullable', 'date', 'after_or_equal:today'],
            'assigned_to'   => ['nullable', 'exists:users,id'],
        ];
    }
}