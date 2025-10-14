<?php

namespace App\Modules\Driver\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $driverId = $this->route('id'); // Obtiene el ID del driver desde la ruta

        return [
            'customer_document_type_id' => 'sometimes|integer|exists:customer_document_types,id',
            'doc_number' => [
                'sometimes',
                'string',
                'max:12',
                Rule::unique('drivers', 'doc_number')->ignore($driverId)
            ],
            'name' => 'sometimes|string|max:20',
            'pat_surname' => 'sometimes|string|max:20',
            'mat_surname' => 'sometimes|string|max:20',
            'license' => [
                'sometimes',
                'string',
                'max:13',
                Rule::unique('drivers', 'license')->ignore($driverId)
            ],
            'status' => 'sometimes|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_document_type_id.integer' => 'El tipo de documento debe ser un número entero.',
            'customer_document_type_id.exists' => 'El tipo de documento seleccionado no es válido.',

            'doc_number.string' => 'El número de documento debe ser una cadena de texto.',
            'doc_number.max' => 'El número de documento no debe exceder los 12 caracteres.',
            'doc_number.unique' => 'El número de documento ya está registrado.',

            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 20 caracteres.',

            'pat_surname.string' => 'El apellido paterno debe ser una cadena de texto.',
            'pat_surname.max' => 'El apellido paterno no debe exceder los 20 caracteres.',

            'mat_surname.string' => 'El apellido materno debe ser una cadena de texto.',
            'mat_surname.max' => 'El apellido materno no debe exceder los 20 caracteres.',

            'license.string' => 'La licencia de conducir debe ser una cadena de texto.',
            'license.max' => 'La licencia de conducir no debe exceder los 13 caracteres.',
            'license.unique' => 'La licencia de conducir ya está registrada.',

            'status.integer' => 'El estado debe ser un número entero.',
            'status.in' => 'El estado debe ser 0 (inactivo) o 1 (activo).',
        ];
    }
}
