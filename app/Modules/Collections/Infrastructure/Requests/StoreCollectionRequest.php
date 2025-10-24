<?php

namespace App\Modules\Collections\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
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
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'parallel_rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'change' => 'nullable|numeric|min:0',
            'digital_wallet_id' => 'nullable|integer|exists:digital_wallets,id',
            'bank_id' => 'nullable|integer|exists:banks,id',
            'operation_date' => 'nullable|date',
            'operation_number' => 'nullable|string|max:20',
            'lote_number' => 'nullable|string|max:20',
            'for_digits' => 'nullable|string|max:4',
        ];
    }
}
