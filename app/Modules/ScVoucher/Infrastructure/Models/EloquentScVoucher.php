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
}
