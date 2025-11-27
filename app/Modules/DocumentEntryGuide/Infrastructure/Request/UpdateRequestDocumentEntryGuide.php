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
            'guide_serie_supplier' => 'required|string|max:10',
            'guide_correlative_supplier' => 'required|string|max:10',
            'invoice_serie_supplier' => 'required|string|max:10',
            'invoice_correlative_supplier' => 'required|string|max:10',
        ];
    }
}