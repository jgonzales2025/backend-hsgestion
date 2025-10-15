<?php

namespace App\Modules\ExchangeRate\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExchangeRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parallel_rate' => 'required|numeric',
        ];
    }

}
