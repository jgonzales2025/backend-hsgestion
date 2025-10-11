<?php

namespace App\Modules\Branch\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                
            'cia_id' => 'sometimes|integer|exists:companies,id',
            'name' => 'sometimes|string|max:30',
            'address' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100',
            'start_date' => 'sometimes|string|max:10',
            'serie' => 'sometimes|string|max:10',
            'status' => 'sometimes|integer',
             'phones' => 'sometimes|array',
            'phones.*' => 'string|max:15' // cada número de teléfono
        ];
    }
}
