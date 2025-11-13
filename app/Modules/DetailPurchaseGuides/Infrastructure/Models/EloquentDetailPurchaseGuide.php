<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDetailPurchaseGuide extends Model{
    protected $table = 'detail_purchase_guide';


    protected $fillable = [
        'article_id',
        'purchase_id',
        'description',
        'cantidad',
        'precio_costo',
        'descuento',
        'sub_total',
    ];

    protected $hidden = ['created_at', 'updated_at'];


}