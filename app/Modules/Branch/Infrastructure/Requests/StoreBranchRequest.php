<?php

namespace App\Modules\Branch\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cia_id' => 'sometimes|integer|exists:companies,id',
            'name' => 'sometimes|string|max:30',
            'address' => 'sometimes|string|max:100',
            'department_id' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'email' => 'sometimes|email|max:100',
            'start_date' => 'sometimes|string|max:10',
            'serie' => 'sometimes|string|max:10',
            'status' => 'sometimes|integer',
        ];
    }
}
