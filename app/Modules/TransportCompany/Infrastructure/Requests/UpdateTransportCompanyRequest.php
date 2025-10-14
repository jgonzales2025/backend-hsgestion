<?php

namespace App\Modules\TransportCompany\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransportCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ruc' => 'sometimes|string|max:11',
            'company_name' => 'sometimes|string|max:100',
            'address' => 'sometimes|string|max:255',
            'nro_reg_mtc' => 'sometimes|string|max:10',
            'status' => 'sometimes|integer',
        ];
    }
}
