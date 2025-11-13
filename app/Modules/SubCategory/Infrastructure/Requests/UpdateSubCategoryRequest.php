<?php

namespace App\Modules\SubCategory\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:30',
            'category_id' => 'sometimes|integer|exists:categories,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'El nombre de la subcategoría debe ser un texto válido.',
            'name.max' => 'El nombre de la subcategoría no puede exceder los 30 caracteres.',

            'category_id.integer' => 'La categoría debe ser un valor numérico válido.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
