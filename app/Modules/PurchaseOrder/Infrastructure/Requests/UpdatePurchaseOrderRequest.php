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
            'observations' => 'nullable|string|max:255',
            'supplier_id' => 'required|integer|exists:customers,id',
            'status' => 'required|integer|in:0,1',
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
