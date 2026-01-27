<?php

namespace App\Modules\PaymentConcept\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentConceptRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required|string|max:150',
            'company_id' => 'required|integer',
        ];
    }
    public function prepareForValidation(): void
    {
        $this->merge([
            'company_id' => request()->get('company_id'),
        ]);
    }

    public function messages()
    {
        return [
            'company_id.nullable' => 'El campo company_id es obligatorio.',
            'description.required' => 'El campo descripción es obligatorio.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'description.max' => 'El campo descripción debe tener un máximo de 150 caracteres.',
        ];
    }
}
