<?php

namespace App\Modules\BuildPc\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuildPcRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $company_id = $this->input('company_id');

        $this->merge([
            'user_id' => auth('api')->id(),
            'company_id' => $company_id,
        ]);
    }

    public function rules()
    {
        return [
            'company_id' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'total_price' => 'required|numeric',
            'user_id' => 'required|numeric',
            'status' => 'nullable|boolean',
            'details' => 'required|array|min:1',
            'details.*.article_id' => 'required|integer|exists:articles,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ];
    }
}
