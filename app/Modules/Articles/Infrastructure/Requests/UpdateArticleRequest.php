<?php

namespace App\Modules\Articles\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }
       protected function prepareForValidation(): void
    {
        // Obtener company_id del payload del token JWT
        $payload = auth('api')->payload();

        $this->merge([
            'user_id' => auth('api')->id()
        ]);
    }

    public function rules():array{
        return [
             'cod_fab'              => 'required|string|max:20',
            'description'          => 'required|string|max:50',
            'weight'               => 'required|numeric|min:0',
            'with_deduction'       => 'required|boolean',
            'series_enabled'       => 'required|boolean',
            'measurement_unit_id'  => 'required|integer|exists:measurement_units,id',
            'brand_id'             => 'required|integer|exists:brands,id',
            'category_id'=>'required|integer|exists:categories,id',
         
            'currency_type_id'     => 'required|integer|exists:currency_types,id',
            'purchase_price'       => 'required|numeric|min:0',
            'public_price'         => 'required|numeric|min:0',
            'distributor_price'    => 'required|numeric|min:0',
            'authorized_price'     => 'required|numeric|min:0',
            'status'               => 'required|integer|exists:statuses,id',
            'user_id'              => 'required|integer|exists:users,id',
            'sub_category_id'      => 'required|integer|exists:sub_categories,id',
            'company_type_id'      => 'required|integer|exists:companies,id',
            'venta'                => 'required|boolean',
            
            // Campos opcionales
            'location'             => 'nullable|string|max:80',
            'warranty'             => 'nullable|string|max:255',
            'tariff_rate'          => 'nullable|numeric|min:0',
            'igv_applicable'       => 'nullable|boolean',
            'plastic_bag_applicable' => 'nullable|boolean',
            'min_stock'            => 'nullable|integer|min:0',
            'cost_to_price_percent' => 'nullable|numeric|min:0',
            'public_price_percent'  => 'nullable|numeric|min:0',
            'distributor_price_percent' => 'nullable|numeric|min:0',
            'authorized_price_percent' => 'nullable|numeric|min:0',
        ];
    }
}