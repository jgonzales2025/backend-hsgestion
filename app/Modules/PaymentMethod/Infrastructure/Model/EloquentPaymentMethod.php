<?php

namespace App\Modules\PaymentMethod\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;

class EloquentPaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
