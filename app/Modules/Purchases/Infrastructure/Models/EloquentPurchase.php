<?php

namespace App\Modules\Purchases\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPurchase extends Model
{
    protected $table = 'purchase';

    protected $fillable = [
        'company_id',
        'branch_id',
        'supplier_id',
        'serie',
        'correlative',
        'exchange_type',
        'payment_type_id',
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
        'total',
        'saldo',
        'is_igv',
        'document_type_id',
        'reference_serie',
        'reference_correlative',
        'saldo',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentType::class, 'payment_type_id');
    }

    public function branches(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function customers(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'supplier_id');
    }

    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency');
    }
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type_id');
    }
}
