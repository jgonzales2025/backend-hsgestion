<?php

namespace App\Modules\Articles\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $companyId = request()->get('company_id');
        $this->merge([
            'user_id' => auth('api')->id(),
            'company_type_id' => $companyId,



            // Conversión de booleanos
            'with_deduction' => filter_var($this->with_deduction, FILTER_VALIDATE_BOOLEAN),
            'series_enabled' => filter_var($this->series_enabled, FILTER_VALIDATE_BOOLEAN),
            'igv_applicable' => filter_var($this->igv_applicable, FILTER_VALIDATE_BOOLEAN),
            'plastic_bag_applicable' => filter_var($this->plastic_bag_applicable, FILTER_VALIDATE_BOOLEAN),
            'venta' => filter_var($this->venta, FILTER_VALIDATE_BOOLEAN),

            // Conversión de numéricos
            'weight' => isset($this->weight) ? (float) $this->weight : 0,
            'tariff_rate' => isset($this->tariff_rate) ? (float) $this->tariff_rate : 0,
            'purchase_price' => isset($this->purchase_price) ? (float) $this->purchase_price : 0,
            'public_price' => isset($this->public_price) ? (float) $this->public_price : 0,
            'distributor_price' => isset($this->distributor_price) ? (float) $this->distributor_price : 0,
            'authorized_price' => isset($this->authorized_price) ? (float) $this->authorized_price : 0,
            'currency_type_id' => isset($this->currency_type_id) ? (int) $this->currency_type_id : 0,
            'statusEsp' => isset($this->statusEsp) ? filter_var($this->statusEsp, FILTER_VALIDATE_BOOLEAN) : false,
           'state_modify_article' => isset($this->state_modify_article) ? (int) $this->state_modify_article : 0,
            'reference_code' => isset($this->reference_code) ? $this->reference_code : [],
            'detail_pc_compatible' => isset($this->detail_pc_compatible) ? $this->detail_pc_compatible : [],
        ]);
    }

    protected function passedValidation(): void
    {
        $user = auth('api')->user();
        $companyId = request()->get('company_id');

        if ($this->hasFile('image_url') && $this->file('image_url')->isValid()) {
            // Guarda la imagen en storage/app/public/articles
            $path = $this->file('image_url')->store('articles', 'public');

            // Genera URL pública: /storage/articles/imagen.png

            $publicUrl = Storage::url($path);
            $this->merge([
                'image_url' => $publicUrl,
                'user_id' => $user->getAuthIdentifier(),
                'company_type_id' => $companyId,
            ]);
        } else {
            $this->merge([
                'image_url' => null,
                'user_id' => $user->getAuthIdentifier(),
            ]);
        }
    }
    public function rules(): array
    {
        return [
            // Campos obligatorios
            'cod_fab' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'with_deduction' => 'nullable|boolean',
            'series_enabled' => 'nullable|boolean',
            'measurement_unit_id' => 'nullable|integer|exists:measurement_units,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'currency_type_id' => 'nullable|integer',
            'purchase_price' => 'nullable|numeric|min:0',
            'public_price' => 'nullable|numeric|min:0',
            'distributor_price' => 'nullable|numeric|min:0',
            'authorized_price' => 'nullable|numeric|min:0',
            'user_id' => 'nullable|integer|exists:users,id',
            'sub_category_id' => 'nullable|integer|exists:sub_categories,id',
            'venta' => 'nullable|boolean',
            'company_type_id' => 'nullable|integer|exists:companies,id',
            'state_modify_article' => 'nullable|integer',
            // Campos opcionales
            'location' => 'nullable|string|max:80',
            'warranty' => 'nullable|string|max:255',
            'tariff_rate' => 'nullable|numeric|min:0',
            'igv_applicable' => 'nullable|boolean',
            'plastic_bag_applicable' => 'nullable|boolean',
            'min_stock' => 'nullable|integer|min:0',
            'public_price_percent' => 'nullable|numeric|min:0',
            'distributor_price_percent' => 'nullable|numeric|min:0',
            'authorized_price_percent' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'filtNameEsp' => 'nullable|string|max:100',
            'statusEsp' => 'nullable|boolean',
            'is_combo' => 'nullable|boolean',
        ];
    }
}
