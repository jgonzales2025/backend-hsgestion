<?php

namespace App\Modules\Advance\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'bank_id' => 'required|exists:banks,id',
            'operation_number' => 'required|string',
            'operation_date' => 'required|date',
            'parallel_rate' => 'required|numeric',
            'currency_type_id' => 'required|exists:currency_types,id',
            'amount' => 'required|numeric',
        ];
    }
}