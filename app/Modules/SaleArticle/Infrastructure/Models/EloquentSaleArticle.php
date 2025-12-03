<?php

namespace App\Modules\SaleArticle\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentSaleArticle extends Model
{
    protected $table = 'sale_article';

    protected $fillable = [
        'sale_id',
        'article_id',
        'description',
        'quantity',
        'unit_price',
        'public_price',
        'subtotal',
        'purchase_price',
        'costo_neto'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(EloquentSale::class, 'sale_id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id');
    }
}
