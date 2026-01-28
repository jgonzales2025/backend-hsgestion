<?php

namespace App\Modules\Advance\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvanceRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            'payment_method_id.required' => 'El método de pago es obligatorio.',
            'payment_method_id.exists' => 'El método de pago seleccionado no existe.',
            'bank_id.required' => 'El banco es obligatorio.',
            'bank_id.exists' => 'El banco seleccionado no existe.',
            'operation_number.required' => 'El número de operación es obligatorio.',
            'operation_number.string' => 'El número de operación debe ser una cadena de texto.',
            'operation_date.required' => 'La fecha de operación es obligatoria.',
            'operation_date.date' => 'La fecha de operación debe ser una fecha válida.',
            'parallel_rate.required' => 'La tasa paralela es obligatoria.',
            'parallel_rate.numeric' => 'La tasa paralela debe ser un valor numérico.',
            'currency_type_id.required' => 'El tipo de moneda es obligatorio.',
            'currency_type_id.exists' => 'El tipo de moneda seleccionado no existe.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un valor numérico.',
        ];
    }
}