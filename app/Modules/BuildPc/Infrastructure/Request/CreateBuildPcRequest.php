<?php

namespace App\Modules\BuildPc\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateBuildPcRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth('api')->id(),
        ]);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            // 'total_price' => 'required|numeric',
            'user_id' => 'required|numeric',
            'status' => 'nullable|boolean',
            'details' => 'required|array|min:1',
            'details.*.article_id' => 'required|integer|exists:articles,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',



            // 'details.*.subtotal' => 'required|numeric|min:0',
        ];
    }
}
