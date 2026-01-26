<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerPortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_ids' => 'required|array|min:1',
            'customer_ids.*' => 'required|integer|exists:customers,id',
            'user_sale_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_ids.required' => 'Debe seleccionar al menos un cliente.',
            'customer_ids.array' => 'El formato de clientes no es válido.',
            'customer_ids.min' => 'Debe seleccionar al menos un cliente.',
            'customer_ids.*.required' => 'El ID del cliente es obligatorio.',
            'customer_ids.*.integer' => 'El ID del cliente debe ser un número entero.',
            'customer_ids.*.exists' => 'El cliente seleccionado no existe.',
            'user_sale_id.required' => 'El ID del usuario es obligatorio.',
            'user_sale_id.integer' => 'El ID del usuario debe ser un número entero.',
            'user_sale_id.exists' => 'El usuario seleccionado no existe.',
        ];
    }

}
