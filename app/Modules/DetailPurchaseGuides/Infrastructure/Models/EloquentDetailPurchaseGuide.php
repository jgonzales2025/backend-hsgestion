<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\DetailPurchaseGuides\Domain\Entities\DetailPurchaseGuide;
use App\Modules\Purchases\Infrastructure\Models\EloquentPurchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getPrecioCosto()
    {
        return $this->precio_costo;
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(EloquentPurchase::class, 'purchase_id');
    }

    public function toDomain(): ?DetailPurchaseGuide
    {
        $domain = new DetailPurchaseGuide(
            id: $this->id,
            article_id: $this->article_id,
            purchase_id: $this->purchase_id,
            description: $this->description,
            cantidad: $this->cantidad,
            precio_costo: $this->precio_costo,
            descuento: $this->descuento,
            sub_total: $this->sub_total,
            total: $this->total,
            cantidad_update: $this->cantidad_update,
            process_status: $this->process_status,
        );

        if ($this->relationLoaded('article') && $this->article) {
            $domain->setArticle($this->article->toDomain($this->article));
        }

        return $domain;
    }
}
