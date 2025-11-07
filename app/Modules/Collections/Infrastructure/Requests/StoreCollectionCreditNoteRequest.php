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

    public function messages(): array
    {
        return [
            'company_id.required' => 'La empresa es obligatoria.',
            'company_id.integer' => 'La empresa debe ser un número entero.',
            'company_id.exists' => 'La empresa seleccionada no existe.',
            'sale_id.required' => 'La venta es obligatoria.',
            'sale_id.integer' => 'La venta debe ser un número entero.',
            'sale_id.exists' => 'La venta seleccionada no existe.',
            'sale_document_type_id.required' => 'El tipo de documento de venta es obligatorio.',
            'sale_document_type_id.integer' => 'El tipo de documento de venta debe ser un número entero.',
            'sale_serie.required' => 'La serie de venta es obligatoria.',
            'sale_serie.string' => 'La serie de venta debe ser una cadena de texto.',
            'sale_serie.max' => 'La serie de venta no puede exceder :max caracteres.',
            'sale_correlative.required' => 'El correlativo de venta es obligatorio.',
            'sale_correlative.string' => 'El correlativo de venta debe ser una cadena de texto.',
            'sale_correlative.max' => 'El correlativo de venta no puede exceder :max caracteres.',
            'payment_method_id.required' => 'El método de pago es obligatorio.',
            'payment_method_id.integer' => 'El método de pago debe ser un número entero.',
            'payment_method_id.exists' => 'El método de pago seleccionado no existe.',
            'payment_date.required' => 'La fecha de pago es obligatoria.',
            'payment_date.date' => 'La fecha de pago debe ser una fecha válida.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto debe ser mayor o igual a 0.',
            'credit_document_type_id.required' => 'El tipo de documento de crédito es obligatorio.',
            'credit_document_type_id.integer' => 'El tipo de documento de crédito debe ser un número entero.',
            'credit_document_type_id.exists' => 'El tipo de documento de crédito seleccionado no existe.',
            'credit_serie.required' => 'La serie de crédito es obligatoria.',
            'credit_serie.string' => 'La serie de crédito debe ser una cadena de texto.',
            'credit_serie.max' => 'La serie de crédito no puede exceder :max caracteres.',
            'credit_correlative.required' => 'El correlativo de crédito es obligatorio.',
            'credit_correlative.string' => 'El correlativo de crédito debe ser una cadena de texto.',
            'credit_correlative.max' => 'El correlativo de crédito no puede exceder :max caracteres.',
        ];
    }
}
