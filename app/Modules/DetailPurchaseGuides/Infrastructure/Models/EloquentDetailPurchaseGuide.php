<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Models;

use App\Modules\DetailPurchaseGuides\Domain\Entities\DetailPurchaseGuide;
use Illuminate\Database\Eloquent\Model;

class EloquentDetailPurchaseGuide extends Model
{
    protected $table = 'detail_purchase_guides';


    protected $fillable = [
        'article_id',
        'purchase_id',
        'description',
        'cantidad',
        'precio_costo',
        'descuento',
        'sub_total',
        'total',
        'cantidad_update',
        'process_status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(): ?DetailPurchaseGuide
    {
        return new DetailPurchaseGuide(
            id: $this->id,
            purchase_id: $this->purchase_id,
            article_id: $this->article_id,
            description: $this->description,
            cantidad: $this->cantidad,
            precio_costo: $this->precio_costo,
            descuento: $this->descuento,
            sub_total: $this->sub_total,
            total: $this->total,
            cantidad_update: $this->cantidad_update,
            process_status: $this->process_status
        );
    }
}
