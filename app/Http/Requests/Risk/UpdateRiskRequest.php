<?php

namespace App\Http\Requests\Risk;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRiskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['sometimes', 'string', 'max:200'],
            'description'   => ['nullable', 'string', 'max:5000'],
            'category'      => ['sometimes', 'string', 'in:technical,organizational,physical,people,compliance'],
            'likelihood'    => ['sometimes', 'integer', 'min:1', 'max:5'],
            'impact'        => ['sometimes', 'integer', 'min:1', 'max:5'],
            'asset_id'      => ['nullable', 'exists:assets,id'],
            'owner_id'      => ['nullable', 'exists:users,id'],
            'status'        => ['sometimes', 'in:identified,assessed,treated,monitored,closed'],
            'identified_at' => ['nullable', 'date'],
        ];
    }
}