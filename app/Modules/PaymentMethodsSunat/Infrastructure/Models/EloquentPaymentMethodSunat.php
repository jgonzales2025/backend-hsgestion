<?php

namespace App\Modules\PaymentMethodsSunat\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPaymentMethodSunat extends Model
{
    protected $table = 'payment_method_sunat';
    protected $primaryKey = 'cod';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'cod',
        'des'
    ];
}
