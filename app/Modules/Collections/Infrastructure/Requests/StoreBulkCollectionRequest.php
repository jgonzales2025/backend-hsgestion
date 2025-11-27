<?php

namespace App\Modules\Collections\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBulkCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'customer_id' => 'required|integer|exists:customers,id',
            "payment_method_id" => "required|integer|exists:payment_methods,id",
            "payment_date" => "required|date",
            "parallel_rate" => "required|numeric",
            "bank_id" => "required|integer|exists:banks,id",
            "currency_type_id" => "required|integer|exists:currency_types,id",
            "operation_date" => "required|date",
            "operation_number" => "required|string",
            "advance_amount" => "nullable|numeric",
            "advance_id" => "nullable|integer|exists:advances,id",
            'collections' => 'required|array|min:1',
            'collections.*.sale_id' => 'required|integer|exists:sales,id',
            'collections.*.sale_document_type_id' => 'required|integer|exists:document_types,id',
            "collections.*.serie" => 'required|string',
            "collections.*.correlative" => 'required|string',
            "collections.*.amount" => 'required|numeric'
        ];
    }
}