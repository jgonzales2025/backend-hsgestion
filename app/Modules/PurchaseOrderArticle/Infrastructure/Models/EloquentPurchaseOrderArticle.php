<?php

namespace App\Modules\PurchaseOrderArticle\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPurchaseOrderArticle extends Model
{
    protected $table = 'purchase_order_article';

    protected $fillable = ['id', 'purchase_order_id', 'article_id', 'description', 'weight', 'quantity', 'purchase_price', 'subtotal'];

    protected $hidden = ['created_at', 'updated_at'];
}
