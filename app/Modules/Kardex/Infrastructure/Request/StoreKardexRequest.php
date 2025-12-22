<?php

namespace App\Modules\Kardex\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreKardexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function prepareForValidation(): void
    {
        $company_id = request()->input('company_id');
        $this->merge([
            'company_id' =>$company_id,
        ]);
    }
    public function rules(): array
    {
        return [
            'company_id' => 'nullable|integer',
            'branch_id' => 'required|integer',
            'codigo' => 'required|string',
            'is_today' => 'nullable|boolean',
            'description' => 'required|string',
            'before_fech' => 'required|date',
            'after_fech' => 'required|date',
            'status' => 'nullable|boolean',

        ];
    }
    public function messages(): array
    {
        return [
            'company_id.required' => 'El campo company_id es requerido',
            'branch_id.required' => 'El campo branch_id es requerido',
            'codigo.required' => 'El campo codigo es requerido',
            'is_today.required' => 'El campo is_today es requerido',
            'description.required' => 'El campo description es requerido',
            'before_fech.required' => 'El campo before_fech es requerido',
            'after_fech.required' => 'El campo after_fech es requerido',
            'status.required' => 'El campo status es requerido',
        ];
    }
}
