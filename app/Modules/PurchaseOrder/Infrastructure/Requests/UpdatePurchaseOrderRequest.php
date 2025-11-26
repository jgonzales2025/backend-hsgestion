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
            'delivery_date' => 'nullable|date',
            'due_date' => 'sometimes|date',
            'days' => 'sometimes|integer',
            'contact' => 'nullable|string|max:100',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'parallel_rate' => 'required|numeric',
            'contact_name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'order_number_supplier' => 'nullable|string|max:50',
            'observations' => 'nullable|string|max:255',
            'supplier_id' => 'required|integer|exists:customers,id',
            'status' => 'required|integer|in:0,1',
            'percentage_igv' => 'required|integer',
            'is_igv_included' => 'required|boolean',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // Articulos
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => 'required|integer|exists:articles,id',
            'articles.*.description' => 'nullable|string|max:150',
            'articles.*.weight' => 'nullable|numeric|min:0',
            'articles.*.quantity' => 'required|integer|min:1',
            'articles.*.purchase_price' => 'required|numeric|min:0',
            'articles.*.subtotal' => 'required|numeric|min:0',
        ];
    }
}
