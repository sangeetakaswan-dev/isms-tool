<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:owner,admin,auditor,assessor,viewer'],
        ];
    }
}