<?php

namespace App\Modules\Branch\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        'cia_id'     => 'sometimes|integer|exists:companies,id',
        'name'       => 'sometimes|string|max:30',
        'address'    => 'sometimes|string|max:100',
        'email'      => 'nullable|email|max:150',
        'start_date' => 'sometimes|string|max:10',
        'serie'      => 'sometimes|string|max:10',
         'status'     => 'sometimes|integer|exists:statuses,id',
        'phones'     => 'nullable|array',
        'phones.*'   => 'nullable|string|max:9',
        
        ];
    }

    public function messages(): array
    {
        return [
            'cia_id.integer'      => 'El ID de la empresa debe ser un número entero.',
            'cia_id.exists'       => 'La empresa seleccionada no existe.',
            
            'name.string'         => 'El nombre debe ser un texto válido.',
            'name.max'            => 'El nombre no puede exceder 30 caracteres.',
            
            'address.string'      => 'La dirección debe ser un texto válido.',
            'address.max'         => 'La dirección no puede exceder 100 caracteres.',
            
            'email.email'         => 'El formato del correo electrónico no es válido.',
            'email.max'           => 'El correo electrónico no puede exceder 150 caracteres.',
            
            'start_date.string'   => 'La fecha de inicio debe ser un texto válido.',
            'start_date.max'      => 'La fecha de inicio no puede exceder 10 caracteres.',
            
            'serie.string'        => 'La serie debe ser un texto válido.',
            'serie.max'           => 'La serie no puede exceder 10 caracteres.',
            
            'status.integer'      => 'El estado debe ser un número entero.',
            'status.exists'       => 'El estado seleccionado no existe.',
            
            'phones.array'        => 'Los teléfonos deben ser un array válido.',
            'phones.*.string'     => 'Cada teléfono debe ser un texto válido.',
            'phones.*.max'        => 'El teléfono no puede exceder 9 caracteres.',
        ];
    }
}
