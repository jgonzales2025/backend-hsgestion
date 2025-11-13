<?php

namespace App\Modules\Purchases\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class PudatePurchaseRequest extends FormRequest{
    public function rule():array{
        return [
            'company_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'serie' => 'required|string',
            'correlative' => 'required|string',
            'exchange_type' => 'required|float',
            'methodpayment' => 'required|string',
            'currency' => 'required|float',
            'date' => 'required|string',
            'date_ven' => 'required|string',
            'days' => 'required|integer',
            'observation' => 'required|string',
            'detraccion' => 'required|float',
            'fech_detraccion' => 'required|string',
            'amount_detraccion' => 'required|float',
            'is_detracion' => 'required|boolean',
            'subtotal' => 'required|float',
            'total_desc' => 'required|float',
            'inafecto' => 'required|float',
            'igv' => 'required|float',
            'total' => 'required|float'
        ];
    }
}