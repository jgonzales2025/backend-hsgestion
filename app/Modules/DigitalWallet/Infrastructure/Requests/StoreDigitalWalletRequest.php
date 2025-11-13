<?php

namespace App\Modules\DigitalWallet\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDigitalWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Obtener company_id del payload del token JWT
        $payload = auth('api')->payload();

        $this->merge([
            'company_id' => $payload->get('company_id'),
            'user_id' => auth('api')->id()
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'phone' => 'required|string|max:11',
            'company_id' => 'required|integer|exists:companies,id',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 30 caracteres.',

            'phone.required' => 'El teléfono es obligatorio.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no puede exceder los 11 caracteres.',

            'company_id.required' => 'El ID de la empresa es obligatorio.',
            'company_id.integer' => 'El ID de la empresa debe ser un número entero.',
            'company_id.exists' => 'La empresa seleccionada no existe.',

            'user_id.required' => 'El ID del usuario es obligatorio.',
            'user_id.integer' => 'El ID del usuario debe ser un número entero.',
            'user_id.exists' => 'El usuario seleccionado no existe.'
        ];
    }
}
