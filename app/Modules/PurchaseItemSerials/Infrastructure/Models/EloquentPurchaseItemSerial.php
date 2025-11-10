<?php

namespace App\Modules\PurchaseItemSerials\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPurchaseItemSerial extends Model{
    protected $table = 'purchase_item_serials';

    protected $fillable = [
        'id',
        'purchase_guide_id',
        'article_id',
        'serial',
    ];
       protected $hidden = ['created_at', 'updated_at'];
}