<?php

namespace App\Modules\User\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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

    public function withValidator($validator)
    {
        /*$validator->after(function ($validator) {
            $assignments = $this->input('assignments', []);

            // Validar que cada branch_id pertenezca a su company_id
            foreach ($assignments as $index => $assignment) {
                if (isset($assignment['company_id']) && isset($assignment['branch_id'])) {

                    // Si branch_id es 0, validar que la compañía tenga sucursales
                    if ($assignment['branch_id'] == 0) {
                        $hasBranches = DB::table('branches')
                            ->where('cia_id', $assignment['company_id'])
                            ->exists();

                        if (!$hasBranches) {
                            $validator->errors()->add(
                                "assignments.{$index}.branch_id",
                                'La compañía seleccionada no tiene sucursales disponibles.'
                            );
                        }
                    } else {
                        // Validar que la sucursal específica pertenezca a la compañía
                        $branchExists = DB::table('branches')
                            ->where('id', $assignment['branch_id'])
                            ->where('cia_id', $assignment['company_id'])
                            ->exists();

                        if (!$branchExists) {
                            $validator->errors()->add(
                                "assignments.{$index}.branch_id",
                                'La sucursal seleccionada no pertenece a la compañía especificada.'
                            );
                        }
                    }
                }
            }
        });*/
    }


    public function messages(): array
    {
        return [
            'username.required' => 'El usuario es obligatorio',
            'username.unique' => 'El usuario ya existe',
            'username.max' => 'El usuario no debe exceder los 20 caracteres',
            'firstname.required' => 'El nombre es obligatorio',
            'lastname.required' => 'El apellido es obligatorio',
            'firstname.max' => 'El nombre no debe exceder los 30 caracteres',
            'lastname.max' => 'El apellido no debe exceder los 60 caracteres',
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
            'user_roles.required' => 'Debe asignar al menos un rol',
            'user_roles.*.role_id.required' => 'El ID del rol es obligatorio',
            'user_roles.*.role_id.exists' => 'Uno o más roles no existen',
            'user_roles.*.custom_permissions.*.exists' => 'Uno o más permisos personalizados no existen',
        ];
    }

}
