<?php
namespace App\Modules\PurchaseGuideArticle\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;


class EloquentPurchaseGuideArticle extends Model
{
    protected $table = 'purchase_guide_article';

    protected $fillable = [
        'purchase_guide_id',
        'article_id',
        'description',
        'quantity',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}