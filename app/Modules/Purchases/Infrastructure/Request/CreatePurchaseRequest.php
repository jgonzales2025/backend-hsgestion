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
            'correlative' => 'required|string',
            'exchange_type' => 'required|numeric',
            'methodpayment' => 'required|string',
            'currency' => 'required|numeric',
            'date' => 'required|string',
            'date_ven' => 'required|string',
            'days' => 'required|integer',
            'observation' => 'required|string',
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
            'shopping_income_guide' => 'required|array',
            // 'shopping_income_guide.*.entry_guide_id' => 'required|integer',

        ];
    }
}
