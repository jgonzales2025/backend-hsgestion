<?php

namespace App\Modules\Collections\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionCreditNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'sale_id' => 'required|integer|exists:sales,id',
            'sale_document_type_id' => 'required|integer',
            'sale_serie' => 'required|string|max:6',
            'sale_correlative' => 'required|string|max:10',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'credit_document_type_id' => 'required|integer|exists:document_types,id',
            'credit_serie' => 'required|string|max:6',
            'credit_correlative' => 'required|string|max:10',
        ];
    }
}
