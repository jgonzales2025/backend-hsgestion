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
            'ruc' => 'required|string|max:11',
            'company_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'nro_reg_mtc' => 'required|string|max:10',
            'status' => 'required|integer',
        ];
    }
}
