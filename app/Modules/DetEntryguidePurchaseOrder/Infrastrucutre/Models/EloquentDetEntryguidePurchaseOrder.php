<?php
namespace App\Modules\DetEntryguidePurchaseOrder\Infrastrucutre\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDetEntryguidePurchaseOrder extends Model{
    
    protected $table = 'det_entry_guide_purchase_order';
    
    protected $fillable = [
        'purchase_order_id',
        'entry_guide_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}