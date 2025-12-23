<?php
namespace App\Modules\DocumentEntryGuide\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestDocumentEntryGuide extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_document_id' => 'required|integer|exists:document_types,id',
            'reference_serie' => 'nullable|string|max:10',
            'reference_correlative' => 'nullable|string|max:10',
        ];
    }
}
