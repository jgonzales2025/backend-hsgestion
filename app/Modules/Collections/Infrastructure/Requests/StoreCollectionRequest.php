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
            'currency_type_id.required' => 'La moneda es obligatoria.',
            'currency_type_id.integer' => 'La moneda debe ser un número entero.',
            'currency_type_id.exists' => 'La moneda seleccionada no existe.',
            'parallel_rate.required' => 'El tipo de cambio es obligatorio.',
            'parallel_rate.numeric' => 'El tipo de cambio debe ser un número.',
            'parallel_rate.min' => 'El tipo de cambio debe ser mayor o igual a 0.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto debe ser mayor o igual a 0.',
            'change.numeric' => 'El cambio debe ser un número.',
            'change.min' => 'El cambio debe ser mayor o igual a 0.',
            'digital_wallet_id.integer' => 'La billetera digital debe ser un número entero.',
            'digital_wallet_id.exists' => 'La billetera digital seleccionada no existe.',
            'bank_id.integer' => 'El banco debe ser un número entero.',
            'bank_id.exists' => 'El banco seleccionado no existe.',
            'operation_date.date' => 'La fecha de operación debe ser una fecha válida.',
            'operation_number.string' => 'El número de operación debe ser una cadena de texto.',
            'operation_number.max' => 'El número de operación no puede exceder :max caracteres.',
            'lote_number.string' => 'El número de lote debe ser una cadena de texto.',
            'lote_number.max' => 'El número de lote no puede exceder :max caracteres.',
            'for_digits.string' => 'Los dígitos para deben ser una cadena de texto.',
            'for_digits.max' => 'Los dígitos para no pueden exceder :max caracteres.',
        ];
    }
}
