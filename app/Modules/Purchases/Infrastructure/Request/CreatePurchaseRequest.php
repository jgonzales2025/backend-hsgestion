<?php

namespace App\Modules\Purchases\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'serie' => 'required|string',
            "entry_guide_id" => 'numeric',
            'exchange_type' => 'required|numeric',
            'methodpayment' => 'required|numeric|exists:payment_methods,id',
            'currency' => 'required|numeric',
            'date' => 'required|string',
            'date_ven' => 'required|string',
            'days' => 'required|integer',
            'observation' => 'nullable|string',
            'detraccion' => 'required|numeric',
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

        ];
    }
    public function messages(): array
    {
        return [
            'det_compras_guia_ingreso.*.descuento.lte' => 'El descuento no puede ser mayor que el sub_total',
        ];
    }
}
