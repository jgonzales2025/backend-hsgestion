<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateDetailPurchaseGuideRequest extends FormRequest
{
    public function rules()
    {
        return [
            'articulo_id' => 'required|intenger',
            'purchase_id' => 'required|integer',
            'description' => 'required|string',
            'cantidad' => 'required|string',
            'precio_costo' => 'required|float',
            'descuento'  => 'required',
            'numeric',
            'sub_total'  => 'required',
            'numeric',
            'total' => 'required|float',
            'cantidad_update' => 'nullable|float',
            'process_status' => 'nullable|string',
        ];
    }
}
