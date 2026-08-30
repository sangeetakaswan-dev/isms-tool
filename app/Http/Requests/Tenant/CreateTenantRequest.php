<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class CreateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'size' => ['required', 'in:1-10,11-50,51-200,201-1000,1000+'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Organization name is required.',
            'size.required' => 'Please select organization size.',
            'contact_email.required' => 'Contact email is required.',
            'contact_email.email' => 'Please provide a valid email address.',
        ];
    }
}