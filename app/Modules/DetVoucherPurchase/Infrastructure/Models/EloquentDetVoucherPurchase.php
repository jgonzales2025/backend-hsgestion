<?php

namespace App\Modules\DetVoucherPurchase\Infrastructure\Models;

use App\Modules\DetVoucherPurchase\Domain\Entities\DetVoucherPurchase;
use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function voucherPurchase():BelongsTo
    {
        return $this->belongsTo(ScVoucher::class, 'voucher_id');
    }

    public function toDomain(): ?DetVoucherPurchase
    {
        return new DetVoucherPurchase(
            id: $this->id,
            voucher_id: $this->voucher_id,
            purchase_id: $this->purchase_id,
            amount: $this->amount,
        );
    }
    
}