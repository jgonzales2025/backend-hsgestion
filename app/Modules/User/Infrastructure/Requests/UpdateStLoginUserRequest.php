<?php

namespace App\Modules\User\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStLoginUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'st_login' => 'required|integer|in:0,1',
        ];
    }
}
