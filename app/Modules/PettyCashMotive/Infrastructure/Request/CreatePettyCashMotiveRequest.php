<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;



class CreatePettyCashMotiveRequest extends FormRequest
{
    public function prepareForValidation(): void
    {

        $companyId = request()->get('company_id');
        $this->merge([
            'user_id' => auth('api')->id(),
            'company_type_id' => $companyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|integer',
            'description' => 'required|string',
            'receipt_type' => 'required|integer',
            'user_id' => 'nullable|integer',
            'date' => 'nullable|string',
            'user_mod' => 'nullable|integer',
            'date_mod' => 'nullable|string',
        ];
    }
}