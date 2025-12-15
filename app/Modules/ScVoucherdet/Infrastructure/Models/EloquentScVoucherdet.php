<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentScVoucherdet extends Model
{
    protected $table = 'sc_voucherdet';
    protected $fillable = [
        'cia',
        'codcon',
        'tipdoc',
        'numdoc',
        'glosa',
        'impsol',
        'impdol',
        'id_purchase',
        'id_sc_voucher',
        'correlativo',
        'serie'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
