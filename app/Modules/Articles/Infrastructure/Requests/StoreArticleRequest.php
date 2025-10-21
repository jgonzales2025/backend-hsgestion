<?php

namespace App\Modules\Articles\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
  
    protected function prepareForValidation(): void
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

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
        ]);
    }
  protected function passedValidation(): void
    {
        if ($this->hasFile('image_url')) {
            $image = $this->file('image_url');

            $destinationPath = public_path('image');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $filename = time() . '_' . $image->getClientOriginalName();

            $image->move($destinationPath, $filename);

            $publicUrl = asset('image/' . $filename);

            // Sobrescribimos el valor de image_url
            $this->merge([
                'image_url' => $publicUrl,
            ]);

            // Log::info('📸 Imagen guardada correctamente en: ' . $publicUrl);
        } else {
            $this->merge([
                'image_url' => null,
            ]);
        }
    }


    public function rules(): array
    {
        return [
            // Campos obligatorios
            'cod_fab' => 'required|string|max:20',
            'description' => 'required|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'with_deduction' => 'required|boolean',
            'series_enabled' => 'required|boolean',
            'measurement_unit_id' => 'required|integer|exists:measurement_units,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'category_id' => 'required|integer|exists:categories,id',

            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'purchase_price' => 'required|numeric|min:0',
            'public_price' => 'required|numeric|min:0',
            'distributor_price' => 'required|numeric|min:0',
            'authorized_price' => 'required|numeric|min:0',
            'status' => 'required|integer|exists:statuses,id',
            'user_id' => 'required|integer|exists:users,id',
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
            'venta' => 'required|boolean',
            'company_type_id' => 'required|integer|exists:companies,id',

            // Campos opcionales
            'location' => 'nullable|string|max:80',
            'warranty' => 'nullable|string|max:255',
            'tariff_rate' => 'nullable|numeric|min:0',
            'igv_applicable' => 'nullable|boolean',
            'plastic_bag_applicable' => 'nullable|boolean',
            'min_stock' => 'nullable|integer|min:0',
            'cost_to_price_percent' => 'nullable|numeric|min:0',
            'public_price_percent' => 'nullable|numeric|min:0',
            'distributor_price_percent' => 'nullable|numeric|min:0',
            'authorized_price_percent' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|image',
        ];
    }
}