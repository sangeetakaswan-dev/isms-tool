<?php

namespace App\Http\Requests\Risk;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy handles this
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:200'],
            'description'   => ['nullable', 'string', 'max:5000'],
            'category'      => ['required', 'string', 'in:technical,organizational,physical,people,compliance'],
            'likelihood'    => ['required', 'integer', 'min:1', 'max:5'],
            'impact'        => ['required', 'integer', 'min:1', 'max:5'],
            'asset_id'      => ['nullable', 'exists:assets,id'],
            'owner_id'      => ['nullable', 'exists:users,id'],
            'status'        => ['nullable', 'in:identified,assessed,treated,monitored,closed'],
            'identified_at' => ['nullable', 'date'],
        ];
    }
}