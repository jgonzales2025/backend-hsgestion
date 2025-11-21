<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;



class CreatePettyCashMotiveRequest extends FormRequest
{
    public function prepareForValidation(): void
    {

        $companyId = request()->get('company_id');
        $this->merge([
            'user_id' => auth('api')->id(),
            'company_id' => $companyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|integer',
            'description' => 'required|string',
            'receipt_type' => 'required|integer|in:18,19',
            'user_id' => 'nullable|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'description.required' => 'El campo descripción es requerido',
            'receipt_type.required' => 'El campo tipo de comprobante es requerido',
            'receipt_type.in' => 'El campo tipo de comprobante debe ser 18 o 19',
        ];
    }
}