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
      'company_id' => $company_id
    ]);
  }
  public function rules(): array
  {
    return [
      'company_id' => 'nullable|integer',
      'branch_id' => 'required|integer|exists:branches,id',
      'supplier_id' => 'required|integer|exists:customers,id',
      'serie' => 'required|string',
      "entry_guide_id" => 'nullable|numeric',
      'exchange_type' => 'nullable|numeric',
      'payment_type_id' => 'required|numeric|exists:payment_types,id',
      'currency_id' => 'required|numeric|exists:currency_types,id',
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
      'det_compras_guia_ingreso.*.precio_costo' => 'required|numeric|gt:0',
      'det_compras_guia_ingreso.*.descuento' => 'required|numeric|min:0',
      'det_compras_guia_ingreso.*.sub_total' => 'required|numeric|gt:0',
      'det_compras_guia_ingreso.*.total' => 'required|numeric|gt:0',
      'det_compras_guia_ingreso.*.cantidad_update' => 'nullable|numeric',
      'det_compras_guia_ingreso.*.process_status' => 'nullable|string',
      'entry_guide_id' => 'required|array',
      'entry_guide_id.*' => 'required|integer|exists:entry_guides,id',
      'is_igv' => 'required|boolean',
      'reference_document_type_id' => 'required|integer',
      'reference_serie' => 'required|string',
      'reference_correlative' => 'required|string',
    ];
  }
  public function messages(): array
  {
    return [
      'det_compras_guia_ingreso.*.cantidad_update.required' => 'La cantidad actualizada es obligatoria',
      'type_document_id.required' => 'El tipo de documento es obligatorio',
      'det_compras_guia_ingreso.*.precio_costo.gt' => 'El precio de costo debe ser mayor a 0',
      'det_compras_guia_ingreso.*.total.gt' => 'El total debe ser mayor a 0',
      'payment_type_id.required' => 'El tipo de pago es obligatorio',
    ];
  }
}
