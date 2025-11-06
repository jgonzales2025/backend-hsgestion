<?php

namespace App\Modules\Collections\Infrastructure\Models;

use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentCollection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        'company_id',
        'sale_id',
        'sale_document_type_id',
        'sale_serie',
        'sale_correlative',
        'payment_method_id',
        'payment_date',
        'currency_type_id',
        'parallel_rate',
        'amount',
        'change',
        'digital_wallet_id',
        'bank_id',
        'operation_date',
        'operation_number',
        'lote_number',
        'for_digits',
        'status',
        'credit_document_type_id',
        'credit_serie',
        'credit_correlative'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(EloquentSale::class, 'sale_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentMethod::class, 'payment_method_id');
    }
}
