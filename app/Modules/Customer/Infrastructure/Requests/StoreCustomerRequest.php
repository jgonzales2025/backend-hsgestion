<?php

namespace App\Modules\Customer\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'record_type_id' => 'required|integer|exists:record_types,id',
            'customer_document_type_id' => 'required|integer|exists:customer_document_types,id',
            'document_number' => 'required|string|max:11|unique:customers,document_number',
            'company_name' => 'required_if:customer_document_type_id,2|string|max:100',
            'name' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'lastname' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'second_lastname' => 'required_if:customer_document_type_id,3,4,5|string|max:50',
            'customer_type_id' => 'required|integer|exists:customer_types,id',
            'fax' => 'nullable|string|max:20',
            'contact' => 'nullable|string|max:100',
            'is_withholding_applicable' => 'required|boolean',
            'status' => 'required|integer',

            'phones' => 'required|array|min:1',
            'phones.*.phone' => 'required|string',

            'addresses' => 'required|array|min:1',
            'addresses.*.address' => 'required|string',
            'addresses.*.department_id' => 'required|integer|exists:departments,coddep',
            'addresses.*.province_id' => 'required|integer|exists:provinces,codpro',
            'addresses.*.district_id' => 'required|integer|exists:districts,coddis',
        ];
    }
}
