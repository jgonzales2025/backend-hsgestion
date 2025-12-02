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
        'impdol'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
