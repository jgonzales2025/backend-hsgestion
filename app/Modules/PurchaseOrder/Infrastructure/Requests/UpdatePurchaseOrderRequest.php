<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'serie' => 'required|string|max:10',
            'date' => 'sometimes|date',
            'delivery_date' => 'sometimes|date',
            'contact' => 'sometimes|string|max:100',
            'order_number_supplier' => 'nullable|string|max:50',
            'supplier_id' => 'required|integer|exists:customers,id',
            'status' => 'required|integer|in:0,1'
        ];
    }
}
