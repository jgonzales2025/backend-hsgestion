<?php
namespace App\Modules\DetailPurchaseGuides\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateDetailPurchaseGuideRequest extends FormRequest{
    public function rule(){
        return [
            'articulo_id' => 'required|intenger',
            'purchase_id' => 'required|integer',
            'description' =>'string',
            'cantidad' => 'string',
            'precio_costo' =>'float',
            'descuento' => 'string',
            'sub_total' =>'float'
        ];
    }
}