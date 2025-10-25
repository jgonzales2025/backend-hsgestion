<?php

namespace App\Modules\Sale\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'document_type_id' => 'required|integer|exists:document_types,id',
            'parallel_rate' => 'required|numeric|min:0',
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'days' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'user_sale_id' => 'required|integer|exists:users,id',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'observations' => 'nullable|string',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'subtotal' => 'required|numeric|min:0',
            'inafecto' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'serie_prof' => 'nullable|string|max:10',
            'correlative_prof' => 'nullable|string|max:10',
            'purchase_order' => 'nullable|string|max:10',

            'sale_articles' => 'required|array|min:1',
            'sale_articles.*.article_id' => 'required|integer|exists:articles,id',
            'sale_articles.*.description' => 'required|string',
            'sale_articles.*.quantity' => 'required|integer|min:1',
            'sale_articles.*.unit_price' => 'required|numeric|min:0',
            'sale_articles.*.public_price' => 'required|numeric|min:0',
            'sale_articles.*.subtotal' => 'required|numeric|min:0',
        ];
    }
}
