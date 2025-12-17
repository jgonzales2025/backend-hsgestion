<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Models;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function voucher():BelongsTo
    {
        return $this->belongsTo(ScVoucher::class, 'id_sc_voucher');
    }
    public function toDomain(): ?ScVoucherdet
    {
    return new ScVoucherdet(
        id: $this->id,
        cia: $this->cia,
        codcon: $this->codcon,
        tipdoc: $this->tipdoc,
        glosa: $this->glosa,
        impsol: $this->impsol,
        impdol: $this->impdol,
        id_purchase: $this->id_purchase,
        id_sc_voucher: $this->id_sc_voucher,
        numdoc: $this->numdoc,
        correlativo: $this->correlativo,
        serie: $this->serie,
    );

    }
}
