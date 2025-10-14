<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('id');
        return [
            'name' =>['required', 'string', Rule::unique('roles', 'name')->ignore($roleId)],
            'menus' => 'nullable|array',
            'menus.*' => 'exists:menus,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.string' => 'El nombre del rol debe ser una cadena de texto.',
            'name.unique' => 'Este nombre de rol ya existe.',
            'menus.array' => 'Los menús deben ser un arreglo válido.',
            'menus.*.integer' => 'Cada menú debe ser un número entero.',
            'menus.*.exists' => 'Uno o más menús seleccionados no son válidos.'
        ];
    }
}
