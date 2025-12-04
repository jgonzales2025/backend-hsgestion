<?php

namespace App\Modules\Purchases\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
      public function authorize(): bool
  {
    return true;
  }
  public function prepareForValidation()
  {
    $company_id = $this->input('company_id');

    $this->merge([
      'company_id' =>$company_id
    ]);
  }
    public function rules(): array
    {
        return [
            'company_id' => 'nullable|integer',
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'serie' => 'required|string',
            "entry_guide_id" => 'numeric',
            'exchange_type' => 'required|numeric',
            'methodpayment_id' => 'required|numeric|exists:payment_methods,id',
            'currency_id' => 'required|numeric',
            'date' => 'required|string',
            'date_ven' => 'required|string',
            'days' => 'required|integer',
            'observation' => 'nullable|string',
            'detraccion' => 'nullable|string',
            'fech_detraccion' => 'nullable|string',
            'amount_detraccion' => 'required|numeric',
            'is_detracion' => 'required|boolean',
            'subtotal' => 'required|numeric',
            'total_desc' => 'required|numeric',
            'inafecto' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'det_compras_guia_ingreso' => 'required|array',
            'det_compras_guia_ingreso.*.article_id' => 'required|integer|exists:articles,id',
            'det_compras_guia_ingreso.*.description' => 'required|string',
            'det_compras_guia_ingreso.*.cantidad' => 'nullable|numeric',
            'det_compras_guia_ingreso.*.precio_costo' => 'required|numeric',
            'det_compras_guia_ingreso.*.descuento' => 'required|numeric',
            'det_compras_guia_ingreso.*.sub_total' => 'required|numeric',
            'det_compras_guia_ingreso.*.total' => 'required|numeric',
            'det_compras_guia_ingreso.*.cantidad_update' => 'required|numeric|',
            //descuento no puede ser mayor que el sub_total
            // 'det_compras_guia_ingreso.*.descuento' => 'required|numeric|lte:det_compras_guia_ingreso.*.sub_total',
        
            //  'det_compras_guia_ingreso.*.cantidad_update' => 'required|numeric|min:0|lte:det_compras_guia_ingreso.*.cantidad',
          
            'det_compras_guia_ingreso.*.process_status' => 'nullable|string',
            'entry_guide' => 'required|array',
            'entry_guide.*' => 'required|integer|exists:entry_guides,id',
            'is_igv' => 'required|boolean',
            'type_document_id' => 'required|integer',
            'reference_serie' => 'required|string',
            'reference_correlative' => 'required|string',

        ];
    }
    public function messages(): array
    {
        return [
            'det_compras_guia_ingreso.*.cantidad_update.required' => 'La cantidad actualizada es obligatoria',
            // 'det_compras_guia_ingreso.*.cantidad_update.numeric' => 'La cantidad actualizada debe ser un numero',
            // 'det_compras_guia_ingreso.*.cantidad_update.min' => 'La cantidad actualizada debe ser mayor o igual a 0',
            // 'det_compras_guia_ingreso.*.cantidad_update.lte' => 'La cantidad actualizada no puede ser mayor que la cantidad',
            // 'det_compras_guia_ingreso.*.descuento.lte' => 'El descuento no puede ser mayor que el sub_total',
            // 'det_compras_guia_ingreso.*.cantidad_update.lte' => 'La cantidad actualizada no puede ser mayor que la cantidad',
        
        ];
    }
}
