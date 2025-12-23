<?php

namespace App\Modules\Company\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'default_currency_type_id' => 'required|exists:currency_types,id',
            'min_profit' => 'required|numeric',
            'max_profit' => 'required|numeric',
            'detrac_cta_banco' => 'nullable|string',
        ];
    }
}