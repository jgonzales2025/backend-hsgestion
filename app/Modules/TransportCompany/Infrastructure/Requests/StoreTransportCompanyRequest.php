<?php

namespace App\Modules\TransportCompany\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ruc' => 'nullable|string|max:11',
            'company_name' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'nro_reg_mtc' => 'nullable|string|max:10'
        ];
    }
}
