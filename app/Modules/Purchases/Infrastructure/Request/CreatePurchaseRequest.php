<?php

namespace App\Modules\Purchases\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'branch_id' => 'required|integer', 
            'serie' => 'required|string',
            "entry_guide_id" => 'numeric',
            'exchange_type' => 'required|numeric',
            'methodpayment_id' => 'required|numeric|exists:payment_methods,id',
            'supplier_id' => 'required|integer|exists:customers,id',
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
            'det_compras_guia_ingreso.*.cantidad' => 'required|numeric',
            'det_compras_guia_ingreso.*.precio_costo' => 'required|numeric',
            'det_compras_guia_ingreso.*.descuento' => 'required|numeric',
            'det_compras_guia_ingreso.*.sub_total' => 'required|numeric',
            'det_compras_guia_ingreso.*.total' => 'required|numeric',
            //descuento no puede ser mayor que el sub_total
            'det_compras_guia_ingreso.*.descuento' => 'required|numeric|lte:det_compras_guia_ingreso.*.sub_total',
            'entry_guide' => 'required|array',
            'entry_guide.*' => 'required|integer|exists:entry_guides,id',

        ];
    }
    public function messages(): array
    {
        return [
            'det_compras_guia_ingreso.*.descuento.lte' => 'El descuento no puede ser mayor que el sub_total',
            'supplier_id.exists' => 'El proveedor no existe',
           'currency_id.exists' => 'La moneda no existe',
        //    'entry_guide.*entry_guide_id.exists' => 'La guía de ingreso no existe',
        ];
    }
}
