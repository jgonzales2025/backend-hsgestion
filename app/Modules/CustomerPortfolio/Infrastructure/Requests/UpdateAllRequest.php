<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAllRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:users,id',
            'newUserId' => 'required|integer|exists:users,id',
        ];
    }
}
