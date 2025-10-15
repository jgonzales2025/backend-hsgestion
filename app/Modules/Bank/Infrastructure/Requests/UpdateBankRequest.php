<?php

namespace App\Modules\Bank\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Obtener company_id del payload del token JWT
        $payload = auth('api')->payload();

        $this->merge([
            'company_id' => $payload->get('company_id'),
            'user_id' => auth('api')->id()
        ]);
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
