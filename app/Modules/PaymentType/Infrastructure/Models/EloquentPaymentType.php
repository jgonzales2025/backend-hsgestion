<?php

namespace App\Modules\PaymentType\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPaymentType extends Model
{
     protected $table = 'payment_types'; 
    protected $fillable = ['name', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
