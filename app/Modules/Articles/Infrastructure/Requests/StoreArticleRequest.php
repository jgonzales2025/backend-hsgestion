<?php

namespace App\Modules\Articles\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }
    public function rules():array{
        return [
             'cod_fab'              => 'sometimes|string|max:20',
        'description'               => 'sometimes|string|max:50',
        'short_description'         => 'sometimes|string|max:100',
        'weight'                    => 'sometimes|numeric|min:0',
        'with_deduction'            => 'sometimes|boolean',
        'series_enabled'            => 'sometimes|boolean',
        'measurement_unit_id'       => 'sometimes|integer',
        'brand_id'                  => 'sometimes|integer|exists:brands,id',
        'category_id'               => 'sometimes|integer|exists:categories,id',
        'location'                  => 'sometimes|string|max:80',
        'warranty'                  => 'sometimes|string|max:255',
        'tariff_rate'               => 'sometimes|numeric|min:0',
        'igv_applicable'            => 'sometimes|boolean',
        'plastic_bag_applicable'    => 'sometimes|boolean',
        'min_stock'                 => 'sometimes|integer|min:0',
        'currency_type_id'          => 'sometimes|integer|exists:currency_types,id',
         'cost_to_price_percent' => 'sometimes|numeric|min:0',
        'purchase_price'            => 'sometimes|numeric|min:0',
        'public_price'              => 'sometimes|numeric|min:0',
        'distributor_price'         => 'sometimes|numeric|min:0',
        'authorized_price'          => 'sometimes|numeric|min:0',
        'public_price_percent'      => 'sometimes|numeric|min:0',
        'distributor_price_percent' => 'sometimes|numeric|min:0',
        'authorized_price_percent'  => 'sometimes|numeric|min:0',
        'status'                    => 'sometimes|integer|exists:statuses,id',
        'user_id'                   => 'sometimes|integer|exists:users,id',
        'subcategoria_id'           => 'sometimes|integer',
          'venta' => 'sometimes|boolean',
        ];
    }
}