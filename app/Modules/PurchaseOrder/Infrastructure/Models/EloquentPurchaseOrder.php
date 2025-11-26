<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'company_id',
        'branch_id',
        'serie',
        'correlative',
        'date',
        'delivery_date',
        'due_date',
        'days',
        'contact_name',
        'contact_phone',
        'currency_type_id',
        'parallel_rate',
        'payment_type_id',
        'order_number_supplier',
        'observations',
        'supplier_id',
        'status',
        'percentage_igv',
        'is_igv_included',
        'subtotal',
        'igv',
        'total'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'supplier_id');
    }

    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_type_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentType::class, 'payment_type_id');
    }
}
