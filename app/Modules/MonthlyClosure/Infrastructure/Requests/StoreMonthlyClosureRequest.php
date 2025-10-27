<?php

namespace App\Modules\MonthlyClosure\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyClosureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => 'required|integer',
            'month' => 'required|integer'
        ];
    }
}
