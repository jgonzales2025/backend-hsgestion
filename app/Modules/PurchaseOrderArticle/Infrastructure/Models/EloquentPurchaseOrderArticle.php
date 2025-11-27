<?php

namespace App\Modules\PurchaseOrderArticle\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPurchaseOrderArticle extends Model
{
    protected $table = 'purchase_order_article';

    protected $fillable = ['id', 'purchase_order_id', 'article_id', 'description', 'weight', 'quantity', 'purchase_price', 'subtotal', 'saldo'];

    protected $hidden = ['created_at', 'updated_at'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id', 'id');
    }
}
