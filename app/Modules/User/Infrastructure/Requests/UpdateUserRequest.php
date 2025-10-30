<?php

namespace App\Modules\User\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'firstname' => 'required|string|max:30',
            'lastname' => 'required|string|max:60',
            'password' => 'nullable|string|confirmed|min:8',
            'password_item' => 'nullable|string',
            'status' => 'required|integer|in:0,1',

            // Assignments
            'assignments' => 'required|array|min:1',
            'assignments.*.company_id' => 'required|integer|exists:companies,id',
            'assignments.*.branch_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('branches')->where('id', $value)->exists()) {
                        $fail('La sucursal seleccionada no existe.');
                    }
                }
            ],

            // User Roles - Nueva estructura
            'user_roles' => 'required|array|min:1',
            'user_roles.*.role_id' => 'required|integer|exists:roles,id',
            'user_roles.*.custom_permissions' => 'nullable|array',
            'user_roles.*.custom_permissions.*' => 'integer|exists:menus,id'
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'El nombre es obligatorio',
            'firstname.max' => 'El nombre no debe exceder los 30 caracteres',
            'lastname.required' => 'El apellido es obligatorio',
            'lastname.max' => 'El apellido no debe exceder los 60 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser 0 o 1',
            'assignments.required' => 'Debe asignar al menos una compañía',
            'assignments.*.company_id.required' => 'El ID de la compañía es obligatorio',
            'assignments.*.company_id.exists' => 'Una o más compañías no existen',
            'assignments.*.branch_id.exists' => 'Una o más sucursales no existen',
            'user_roles.required' => 'Debe asignar al menos un rol',
            'user_roles.*.role_id.required' => 'El ID del rol es obligatorio',
            'user_roles.*.role_id.exists' => 'Uno o más roles no existen',
            'user_roles.*.custom_permissions.*.exists' => 'Uno o más permisos personalizados no existen',
        ];
    }


}
