<?php

namespace App\Modules\Purchases\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest{
    public function rules():array{
        return [
            'company_id' => 'required|integer',
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
            'fech_detraccion' => 'required|string',
            'amount_detraccion' => 'required|numeric',
            'is_detracion' => 'required|boolean',
            'subtotal' => 'required|numeric',
            'total_desc' => 'required|numeric',
            'inafecto' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'det_compras_guia_ingreso' => 'required|array',
              'shopping_income_guide' => 'required|array',
        ];
    }
}
