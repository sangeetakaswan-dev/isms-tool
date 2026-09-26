<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'document_type' => 'required|in:policy,procedure,record,template,evidence',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'review_date' => 'nullable|date',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
        ];
    }
}
