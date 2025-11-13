<?php

namespace App\Modules\DigitalWallet\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDigitalWalletRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'phone' => 'required|string|max:11',
            'company_id' => 'required|integer|exists:companies,id',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
