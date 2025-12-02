<?php

namespace App\Modules\ScVoucher\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentScVoucher extends Model
{
    protected $table = 'supplier_payment_registration';

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
        'fecmod',
    ];
    protected $hidden = ['updated_at', 'created_at'];
}
