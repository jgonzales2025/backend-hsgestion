<?php

namespace App\Modules\Articles\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cod_fab' => 'required|string|max:20',
            'description' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:255',
            'weight' => 'required|numeric',
            'with_deduction' => 'required|boolean',
            'series_enabled' => 'required|boolean',
            'measurement_unit_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'category_id' => 'required|integer',
            'location' => 'nullable|string|max:50',
            'warranty' => 'nullable|string|max:50',
            'tariff_rate' => 'required|numeric',
            'igv_applicable' => 'required|boolean',
            'plastic_bag_applicable' => 'required|boolean',
            'min_stock' => 'required|integer',
            'currency_type_id' => 'required|integer',
            'cost_to_price_percent' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'public_price' => 'required|numeric',
            'distributor_price' => 'required|numeric',
            'authorized_price' => 'required|numeric',
            'public_price_percent' => 'required|numeric',
            'distributor_price_percent' => 'required|numeric',
            'authorized_price_percent' => 'required|numeric',
            'status' => 'required|integer',
            'user_id' => 'nullable|integer',
            'venta' => 'required|boolean'
        ];
    }
}
