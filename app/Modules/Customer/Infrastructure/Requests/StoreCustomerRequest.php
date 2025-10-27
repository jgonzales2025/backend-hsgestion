<?php

namespace App\Modules\Customer\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'record_type_id' => 'required|integer|exists:record_types,id',
            'customer_document_type_id' => 'required|integer|exists:customer_document_types,id',
            'document_number' => 'required|string|max:11|unique:customers,document_number',
            'company_name' => 'required_if:customer_document_type_id,2|string|max:100',
            'name' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'lastname' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'second_lastname' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'customer_type_id' => 'required|integer|exists:customer_types,id',
            'contact' => 'nullable|string|max:100',
            'is_withholding_applicable' => 'required|boolean',
            'status' => 'required|integer',

            'phones' => 'required|array|min:1',
            'phones.*.phone' => 'required|string',

            'emails' => 'required|array|min:1',
            'emails.*.email' => 'required|email',

            'addresses' => 'required|array|min:1',
            'addresses.*.address' => 'required|string',
            'addresses.*.department_id' => 'required|integer|exists:departments,coddep',
            'addresses.*.province_id' => 'required|integer|exists:provinces,codpro',
            'addresses.*.district_id' => 'required|integer|exists:districts,coddis',
        ];
    }

    public function messages(): array
    {
        return [
            'record_type_id.required' => 'El tipo de registro es obligatorio.',
            'record_type_id.integer' => 'El tipo de registro debe ser un número entero.',
            'record_type_id.exists' => 'El tipo de registro seleccionado no existe.',

            'customer_document_type_id.required' => 'El tipo de documento es obligatorio.',
            'customer_document_type_id.integer' => 'El tipo de documento debe ser un número entero.',
            'customer_document_type_id.exists' => 'El tipo de documento seleccionado no existe.',

            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.string' => 'El número de documento debe ser una cadena de texto.',
            'document_number.max' => 'El número de documento no debe exceder 11 caracteres.',
            'document_number.unique' => 'El número de documento ya está registrado.',

            'company_name.required_if' => 'La razón social es obligatoria para el tipo de documento seleccionado.',
            'company_name.string' => 'La razón social debe ser una cadena de texto.',
            'company_name.max' => 'La razón social no debe exceder 100 caracteres.',

            'name.required_if' => 'El nombre es obligatorio para el tipo de documento seleccionado.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder 50 caracteres.',

            'lastname.required_if' => 'El apellido paterno es obligatorio para el tipo de documento seleccionado.',
            'lastname.string' => 'El apellido paterno debe ser una cadena de texto.',
            'lastname.max' => 'El apellido paterno no debe exceder 50 caracteres.',

            'second_lastname.required_if' => 'El apellido materno es obligatorio para el tipo de documento seleccionado.',
            'second_lastname.string' => 'El apellido materno debe ser una cadena de texto.',
            'second_lastname.max' => 'El apellido materno no debe exceder 50 caracteres.',

            'customer_type_id.required' => 'El tipo de cliente es obligatorio.',
            'customer_type_id.integer' => 'El tipo de cliente debe ser un número entero.',
            'customer_type_id.exists' => 'El tipo de cliente seleccionado no existe.',

            'contact.string' => 'El contacto debe ser una cadena de texto.',
            'contact.max' => 'El contacto no debe exceder 100 caracteres.',

            'is_withholding_applicable.required' => 'Debe indicar si aplica retención.',
            'is_withholding_applicable.boolean' => 'El valor de retención debe ser verdadero o falso.',

            'status.required' => 'El estado es obligatorio.',
            'status.integer' => 'El estado debe ser un número entero.',

            'phones.required' => 'Debe ingresar al menos un teléfono.',
            'phones.array' => 'El campo teléfonos debe ser un arreglo.',
            'phones.*.phone.required' => 'El número de teléfono es obligatorio.',
            'phones.*.phone.string' => 'El número de teléfono debe ser una cadena de texto.',

            'emails.required' => 'Debe ingresar al menos un correo electrónico.',
            'emails.array' => 'El campo correos debe ser un arreglo.',
            'emails.*.email.required' => 'El correo electrónico es obligatorio.',
            'emails.*.email.email' => 'El correo electrónico debe ser válido.',

            'addresses.required' => 'Debe ingresar al menos una dirección.',
            'addresses.array' => 'El campo direcciones debe ser un arreglo.',
            'addresses.*.address.required' => 'La dirección es obligatoria.',
            'addresses.*.address.string' => 'La dirección debe ser una cadena de texto.',
            'addresses.*.department_id.required' => 'El departamento es obligatorio.',
            'addresses.*.department_id.integer' => 'El departamento debe ser un número entero.',
            'addresses.*.department_id.exists' => 'El departamento seleccionado no existe.',
            'addresses.*.province_id.required' => 'La provincia es obligatoria.',
            'addresses.*.province_id.integer' => 'La provincia debe ser un número entero.',
            'addresses.*.province_id.exists' => 'La provincia seleccionada no existe.',
            'addresses.*.district_id.required' => 'El distrito es obligatorio.',
            'addresses.*.district_id.integer' => 'El distrito debe ser un número entero.',
            'addresses.*.district_id.exists' => 'El distrito seleccionado no existe.',
        ];
    }

}
