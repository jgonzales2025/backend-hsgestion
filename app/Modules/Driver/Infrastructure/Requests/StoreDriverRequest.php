<?php

namespace App\Modules\Driver\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_document_type_id' => 'required|integer|exists:customer_document_types,id',
            'doc_number' => 'required|string|max:12',
            'name' => 'required|string|max:20',
            'pat_surname' => 'required|string|max:20',
            'mat_surname' => 'required|string|max:20',
            'license' => 'required|string|max:13'
        ];
    }

    public function messages(): array
    {
        return [
            'customer_document_type_id.required' => 'El tipo de documento es obligatorio.',
            'customer_document_type_id.integer' => 'El tipo de documento debe ser un número entero.',
            'customer_document_type_id.exists' => 'El tipo de documento seleccionado no es válido.',

            'doc_number.required' => 'El número de documento es obligatorio.',
            'doc_number.string' => 'El número de documento debe ser una cadena de texto.',
            'doc_number.max' => 'El número de documento no debe exceder los 12 caracteres.',

            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 20 caracteres.',

            'pat_surname.required' => 'El apellido paterno es obligatorio.',
            'pat_surname.string' => 'El apellido paterno debe ser una cadena de texto.',
            'pat_surname.max' => 'El apellido paterno no debe exceder los 20 caracteres.',

            'mat_surname.required' => 'El apellido materno es obligatorio.',
            'mat_surname.string' => 'El apellido materno debe ser una cadena de texto.',
            'mat_surname.max' => 'El apellido materno no debe exceder los 20 caracteres.',

            'license.required' => 'La licencia de conducir es obligatoria.',
            'license.string' => 'La licencia de conducir debe ser una cadena de texto.',
            'license.max' => 'La licencia de conducir no debe exceder los 13 caracteres.',
        ];
    }
}
