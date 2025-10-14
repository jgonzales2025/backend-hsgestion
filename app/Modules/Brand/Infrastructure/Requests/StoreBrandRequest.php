<?php

namespace App\Modules\Brand\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:30',
            'status' => 'required|integer',
        ];
    }
}
