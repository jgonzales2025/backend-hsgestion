<?php

namespace App\Modules\PercentageIGV\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePercentageIGVRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'sometimes|date|unique:percentage_igvs,date,' . $this->route('id'),
            'percentage' => 'sometimes|integer|min:0|max:100',
        ];
    }
}
