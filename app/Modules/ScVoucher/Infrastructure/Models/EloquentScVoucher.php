<?php

namespace App\Modules\ScVoucher\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentScVoucher extends Model
{
    protected $table = 'sc_voucher';

    protected $fillable = [
        'id',
        'cia',
        'anopr',
        'correlativo',
        'fecha',
        'codban',
        'codigo',
        'nroope',
        'glosa',
        'orden',
        'tipmon',
        'tipcam',
        'total',
        'medpag',
        'tipopago',
        'status',
        'usradi',
        'fecadi',
        'usrmod',
    ];
    protected $hidden = ['updated_at', 'created_at'];

    // Relaciones
    public function customer()
    {
        return $this->belongsTo(\App\Modules\Customer\Infrastructure\Models\EloquentCustomer::class, 'codigo', 'id');
    }

    public function currencyType()
    {
        return $this->belongsTo(\App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType::class, 'tipmon', 'id');
    }

    public function paymentMethodSunat()
    {
        return $this->belongsTo(\App\Modules\PaymentMethodsSunat\Infrastructure\Models\EloquentPaymentMethodSunat::class, 'medpag', 'id');
    }

    public function paymentType()
    {
        return $this->belongsTo(\App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType::class, 'tipopago', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(\App\Modules\Bank\Infrastructure\Models\EloquentBank::class, 'codban', 'id');
    }
}
