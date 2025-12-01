<?php

namespace App\Modules\Advance\Infrastructure\Models;

use App\Modules\Bank\Infrastructure\Models\EloquentBank;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;
use Illuminate\Database\Eloquent\Model;

class EloquentAdvance extends Model
{
    protected $table = 'advances';

    protected $fillable = [
        'company_id',
        'correlative',
        'customer_id',
        'payment_method_id',
        'bank_id',
        'operation_number',
        'operation_date',
        'parallel_rate',
        'currency_type_id',
        'amount',
        'saldo',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function customer()
    {
        return $this->belongsTo(EloquentCustomer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(EloquentPaymentMethod::class);
    }

    public function bank()
    {
        return $this->belongsTo(EloquentBank::class);
    }

    public function currencyType()
    {
        return $this->belongsTo(EloquentCurrencyType::class);
    }
}