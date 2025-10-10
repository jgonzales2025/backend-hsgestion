<?php

namespace App\Modules\PercentageIGV\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePercentageIGVRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'percentage' => 'required|integer|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe tener un formato válido.',

            'percentage.required' => 'El porcentaje es obligatorio.',
            'percentage.integer' => 'El porcentaje debe ser un número entero.',
            'percentage.min' => 'El porcentaje debe ser mayor o igual a 0.',
            'percentage.max' => 'El porcentaje no puede ser mayor a 100.',
        ];
    }
}
