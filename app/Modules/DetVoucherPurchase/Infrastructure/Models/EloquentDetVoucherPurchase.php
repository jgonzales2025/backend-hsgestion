<?php

namespace App\Modules\DetVoucherPurchase\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDetVoucherPurchase extends Model
{
    protected $table = 'det_voucher_purchase';
    protected $fillable = [
        'voucher_id',
        'purchase_id',
        'amount',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
}