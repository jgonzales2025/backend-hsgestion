<?php
namespace App\Modules\Purchases\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPurchase extends Model{
    protected $table = 'purchase';
    
    protected $fillable = [
        'company_id',
        'branch_id',
        'supplier_id',
        'serie',
        'correlative',
        'exchange_type',
        'methodpayment',
        'currency',
        'date',
        'date_ven',
        'days',
        'observation',
        'detraccion',
        'fech_detraccion',
        'amount_detraccion',
        'is_detracion',
        'subtotal',
        'total_desc',
        'inafecto',
        'igv',
        'total'
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
}