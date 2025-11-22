<?php
namespace App\Modules\Purchases\Infrastructure\Models;

use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPurchase extends Model{
    protected $table = 'purchase';
    
    protected $fillable = [
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
       public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentMethod::class, 'methodpayment');
    }

}