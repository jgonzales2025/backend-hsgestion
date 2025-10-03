<?php

namespace App\Modules\User\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:20|unique:users,username',
            'firstname' => 'required|string|max:30',
            'lastname' => 'required|string|max:60',
            'password' => 'required|string|confirmed|min:8',
            'status' => 'required|integer',
            'role_id' => 'required|integer|exists:roles,id',

            //Assignments
            'assignments' => 'required|array|min:1',
            'assignments.*.company_id' => 'required|integer|exists:companies,id',
            'assignments.*.branch_id' => 'required|integer|exists:branches,id'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El usuario es obligatorio',
            'username.unique' => 'El usuario ya existe',
            'firstname.required' => 'El nombre es obligatorio',
            'lastname.required' => 'El apellido es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'status.required' => 'El estado es obligatorio',
            'role_id.required' => 'El rol es obligatorio',
            'role_id.exists' => 'El rol seleccionado no existe',
            'assignments.required' => 'Debe asignar al menos una compañía',
            'assignments.*.company_id.required' => 'El ID de la compañía es obligatorio',
            'assignments.*.company_id.exists' => 'Una o más compañías no existen',
            'assignments.*.branch_id.required' => 'El ID de la sucursal es obligatorio',
            'assignments.*.branch_id.exists' => 'Una o más sucursales no existen',
        ];
    }

}
