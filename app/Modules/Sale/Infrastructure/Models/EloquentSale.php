<?php

namespace App\Modules\Sale\Infrastructure\Models;

use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentSale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'company_id',
        'document_type_id',
        'serie',
        'document_number',
        'parallel_rate',
        'customer_id',
        'date',
        'due_date',
        'days',
        'user_id',
        'payment_type_id',
        'observations',
        'currency_type_id',
        'subtotal',
        'igv',
        'total',
        'status',
        'is_locked'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentType::class, 'payment_type_id');
    }

    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_type_id');
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }
}
