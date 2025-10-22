<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerPortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
