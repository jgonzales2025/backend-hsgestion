<?php

namespace App\Modules\Bank\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'account_number' => 'required|string|max:20',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'user_id' => 'required|integer|exists:users,id',
            'company_id' => 'required|integer|exists:companies,id',
            'status' => 'required|integer',
        ];
    }
}
