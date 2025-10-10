<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMeasurementUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30|unique:measurement_units,name',
            'abbreviation' => 'required|string|max:10|unique:measurement_units,abbreviation',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 30 caracteres.',
            'name.unique' => 'El nombre ya está registrado.',

            'abbreviation.required' => 'La abreviatura es obligatoria.',
            'abbreviation.string' => 'La abreviatura debe ser una cadena de texto.',
            'abbreviation.max' => 'La abreviatura no puede exceder los 10 caracteres.',
            'abbreviation.unique' => 'La abreviatura ya está registrada.',

            'status.required' => 'El estado es obligatorio.',
            'status.integer' => 'El estado debe ser un valor numérico entero.',
            'status.in' => 'El estado debe ser 0 (inactivo) o 1 (activo).',
        ];
    }
}
