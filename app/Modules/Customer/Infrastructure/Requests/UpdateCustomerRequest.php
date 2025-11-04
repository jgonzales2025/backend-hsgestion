<?php

namespace App\Modules\Customer\Infrastructure\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('id');
        return [
            'id' => 'required|exists:customers,id',
            'record_type_id' => 'required|integer|exists:record_types,id',
            'customer_document_type_id' => 'required|integer|exists:customer_document_types,id',
            'document_number' => ['required', 'string', 'max:11', Rule::unique('customers', 'document_number')->ignore($customerId)],
            'company_name' => 'required_if:customer_document_type_id,2|string|max:100',
            'name' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'lastname' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'second_lastname' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'customer_type_id' => 'required|integer|exists:customer_types,id',
            'contact' => 'nullable|string|max:100',
            'is_withholding_applicable' => 'sometimes|boolean',
            'status' => 'sometimes|integer',

            'phones' => 'sometimes|array|min:1',
            'phones.*.phone' => 'sometimes|string',
            'phones.*.status' => 'sometimes|integer|in:0,1',

            'emails' => 'sometimes|array|min:1',
            'emails.*.email' => 'sometimes|email',
            'emails.*.status' => 'sometimes|integer|in:0,1',

            'addresses' => 'sometimes|array|min:1',
            'addresses.*.address' => 'sometimes|string',
            'addresses.*.st_principal' => 'sometimes|integer|in:0,1',
            'addresses.*.department_id' => 'sometimes|integer|exists:departments,coddep',
            'addresses.*.province_id' => 'sometimes|integer|exists:provinces,codpro',
            'addresses.*.district_id' => 'sometimes|integer|exists:districts,coddis',
            'addresses.*.status' => 'sometimes|integer|in:0,1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Si el ID no existe, mostrar solo ese error
        if ($errors->has('id')) {
            $response = response()->json([
                'message' => $errors->first('id'),
                'errors' => [
                    'id' => $errors->get('id')
                ]
            ], 422);

            throw new HttpResponseException($response);
        }

        parent::failedValidation($validator);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El ID del cliente es requerido.',
            'id.exists' => 'El cliente no existe.',
            'record_type_id.required' => 'El tipo de registro es requerido.',
            'record_type_id.integer' => 'El tipo de registro debe ser un número entero.',
            'record_type_id.exists' => 'El tipo de registro no existe.',
            'customer_document_type_id.required' => 'El tipo de documento es requerido.',
            'customer_document_type_id.integer' => 'El tipo de documento debe ser un número entero.',
            'customer_document_type_id.exists' => 'El tipo de documento no existe.',
            'document_number.required' => 'El número de documento es requerido.',
            'document_number.string' => 'El número de documento debe ser una cadena de texto.',
            'document_number.max' => 'El número de documento no debe exceder los 11 caracteres.',
            'document_number.unique' => 'El número de documento ya está registrado.',
            'company_name.required_if' => 'La razón social es requerida para RUC.',
            'company_name.string' => 'La razón social debe ser una cadena de texto.',
            'company_name.max' => 'La razón social no debe exceder los 100 caracteres.',
            'name.required_if' => 'El nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 50 caracteres.',
            'lastname.required_if' => 'El apellido paterno es requerido.',
            'lastname.string' => 'El apellido paterno debe ser una cadena de texto.',
            'lastname.max' => 'El apellido paterno no debe exceder los 50 caracteres.',
            'second_lastname.required_if' => 'El apellido materno es requerido.',
            'second_lastname.string' => 'El apellido materno debe ser una cadena de texto.',
            'second_lastname.max' => 'El apellido materno no debe exceder los 50 caracteres.',
            'customer_type_id.required' => 'El tipo de cliente es requerido.',
            'customer_type_id.integer' => 'El tipo de cliente debe ser un número entero.',
            'customer_type_id.exists' => 'El tipo de cliente no existe.',
            'fax.string' => 'El fax debe ser una cadena de texto.',
            'fax.max' => 'El fax no debe exceder los 20 caracteres.',
            'contact.string' => 'El contacto debe ser una cadena de texto.',
            'contact.max' => 'El contacto no debe exceder los 100 caracteres.',
            'is_withholding_applicable.required' => 'Debe especificar si aplica retención.',
            'is_withholding_applicable.boolean' => 'El campo aplica retención debe ser verdadero o falso.',
            'status.integer' => 'El estado debe ser un número entero.',
            'phones.array' => 'Los teléfonos deben ser un arreglo.',
            'phones.min' => 'Debe proporcionar al menos un teléfono.',
            'phones.*.phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phones.*.status.integer' => 'El estado del teléfono debe ser un número entero.',
            'phones.*.status.in' => 'El estado del teléfono debe ser 0 o 1.',
            'emails.array' => 'Los correos electrónicos deben ser un arreglo.',
            'emails.min' => 'Debe proporcionar al menos un correo electrónico.',
            'emails.*.email.required' => 'El correo electrónico es requerido.',
            'emails.*.email.email' => 'El correo electrónico debe ser válido.',
            'addresses.required' => 'Debe proporcionar al menos una dirección.',
            'addresses.array' => 'Las direcciones deben ser un arreglo.',
            'addresses.min' => 'Debe proporcionar al menos una dirección.',
            'addresses.*.address.required' => 'La dirección es requerida.',
            'addresses.*.address.string' => 'La dirección debe ser una cadena de texto.',
            'addresses.*.department_id.required' => 'El departamento es requerido.',
            'addresses.*.department_id.integer' => 'El departamento debe ser un número entero.',
            'addresses.*.department_id.exists' => 'El departamento no existe.',
            'addresses.*.province_id.required' => 'La provincia es requerida.',
            'addresses.*.province_id.integer' => 'La provincia debe ser un número entero.',
            'addresses.*.province_id.exists' => 'La provincia no existe.',
            'addresses.*.district_id.required' => 'El distrito es requerido.',
            'addresses.*.district_id.integer' => 'El distrito debe ser un número entero.',
            'addresses.*.district_id.exists' => 'El distrito no existe.',
        ];
    }

}
