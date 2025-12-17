<?php

namespace App\Modules\ScVoucher\Infrastructure\Models;

use App\Modules\Bank\Infrastructure\Models\EloquentBank;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\DetVoucherPurchase\Infrastructure\Models\EloquentDetVoucherPurchase;
use App\Modules\PaymentMethodsSunat\Infrastructure\Models\EloquentPaymentMethodSunat;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;
use App\Modules\ScVoucherdet\Infrastructure\Models\EloquentScVoucherdet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(EloquentCustomer::class, 'codigo', 'id');
    }

    public function currencyType()
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'tipmon', 'id');
    }

    public function paymentMethodSunat()
    {
        return $this->belongsTo(EloquentPaymentMethodSunat::class, 'medpag', 'id');
    }

    public function paymentType()
    {
        return $this->belongsTo(EloquentPaymentType::class, 'tipopago', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(EloquentBank::class, 'codban', 'id');
    }
    public function details(): HasMany
    {
        return $this->hasMany(EloquentScVoucherdet::class, 'id_sc_voucher');
    }
    public function detailVoucherPurchase(): HasMany
    {
        return $this->hasMany(EloquentDetVoucherPurchase::class, 'voucher_id');
    }
    public function toDomain(): ScVoucher
    {
        return new ScVoucher(
            id: $this->id,
            cia: $this->cia,
            anopr: $this->anopr,
            correlativo: $this->correlativo,
            fecha: $this->fecha,
            codban: $this->bank?->toDomain($this->bank),
            codigo: $this->customer?->toDomain($this->customer),
            nroope: $this->nroope,
            glosa: $this->glosa,
            orden: $this->orden,
            tipmon: $this->currencyType?->toDomain($this->currencyType),
            tipcam: $this->tipcam,
            total: $this->total,
            medpag: $this->paymentMethodSunat?->toDomain($this->paymentMethodSunat),
            tipopago: $this->paymentType?->toDomain($this->paymentType),
            status: $this->status,
            usradi: $this->usradi,
            fecadi: $this->fecadi,
            usrmod: $this->usrmod,
            details: $this->details->map(fn($d) => $d->toDomain())->toArray(),
            detailVoucherpurchase: $this->detailVoucherPurchase->map(fn($d) => $d->toDomain())->toArray(),
        );
    }
}
