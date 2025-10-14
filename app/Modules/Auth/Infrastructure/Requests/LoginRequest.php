<?php

namespace App\Modules\Auth\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
            'cia_id' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El usuario es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
            'cia_id.required' => 'La compañía es obligatoria',
            'cia_id.integer' => 'La compañía debe ser un número válido',
        ];
    }
}
