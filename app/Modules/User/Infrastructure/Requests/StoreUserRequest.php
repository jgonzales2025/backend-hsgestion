<?php

namespace App\Modules\User\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:20|unique:users,username',
            'firstname' => 'required|string|max:30',
            'lastname' => 'required|string|max:60',
            'password' => 'required|string|confirmed|min:8',
            'status' => 'required|integer',
            'companies' => 'required|array',
            'companies.*' => 'exists:companies,id',
            'branch' => 'required|integer',
            'role' => 'required|integer|exists:roles,id'
        ];
    }
}
