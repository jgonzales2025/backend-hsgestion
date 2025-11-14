<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreatePettyCashReceiptRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $companyId = request()->get('company_id');

        $this->merge([
            'company_id' => $companyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|integer',
            'document_type' => 'required|integer',
            'series' => 'required|string',
            'correlative' => 'string',
            'date' => 'required|string',
            'delivered_to' => 'required|string',
            'reason_code' => 'required|integer',
            'currency_type' => 'required|integer',
            'amount' => 'required|numeric',
            'observation' => 'nullable|string',
            'status' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'created_at_manual' => 'nullable|string',
            'updated_by' => 'nullable|integer',
            'updated_at_manual' => 'nullable|string',
            'branch_id' => 'required|integer'
        ];
    }
}